<?php

namespace BlueLibraries\Dns\Handlers\Raw;

use BlueLibraries\Dns\Handlers\DnsHandlerException;
use BlueLibraries\Dns\Handlers\DnsHandlerTypes;
use BlueLibraries\Dns\Records\RecordTypes;
use BlueLibraries\Dns\Records\DnsUtils;

class RawDataResponse
{

    private RawDataRequest $request;
    private ?string $rawResponse = null;
    private ?string $rawBuffer = null;
    private ?string $rawResponseHeader = null;
    private array $headerData = [];
    private int $responseCounter = 12;
    private ?array $questions = null;
    private ?array $answers = null;
    private ?string $handlerType = null;
    private ?array $lastResult = null;
    private int $lastIndex = 0;

    /**
     * @param RawDataRequest $request
     * @param string $rawData
     * @param string $handlerType
     * @throws DnsHandlerException
     */
    public function __construct(RawDataRequest $request, string $rawData, string $handlerType)
    {
        if (strlen($rawData) >= 12) {
            $this->rawResponseHeader = substr($rawData, 0, 12);
            $this->rawResponse = substr($rawData, 12);
            $this->rawBuffer = $rawData;
            $this->headerData = unpack("nid/nspec/nqdcount/nancount/nnscount/narcount", $this->rawResponseHeader);
        }
        $this->request = $request;
        $this->handlerType = $handlerType;
        $this->validateHeaderData();
    }

    /**
     * @return void
     * @throws DnsHandlerException
     */
    private function validateHeaderData()
    {
        if (
            $this->handlerType === DnsHandlerTypes::UDP
            && $this->headerIsTruncated()
        ) {
            throw new DnsHandlerException(
                'Response too big for UDP, truncation detected, retry TCP or DI... or else!' .
                ' domain: ' . json_encode($this->request->getDomain() . ' typeId:' . json_encode($this->request->getTypeId()) .
                    ' typeName: ' . RecordTypes::getName($this->request->getTypeId()),
                ),
                DnsHandlerException::TRUNCATION_DETECTED
            );
        }
    }

    private function getHeaderAnswersCount(): int
    {
        return $this->headerData['ancount'] ?? 0;
    }

    private function getHeaderQuestionsCount(): int
    {
        return $this->headerData['qdcount'] ?? 0;
    }

    function readResponse(int $count = 1, int $offset = null): string
    {
        if (is_null($offset)) {
            $return = substr($this->rawBuffer, $this->responseCounter, $count);
            $this->responseCounter += $count;
        } else {
            $return = substr($this->rawBuffer, $offset, $count);
        }
        return $return;
    }

    function getDomainLabel(): string
    {
        $count = 0;
        $labels = $this->getDomainLabels($this->responseCounter, $count);
        $domain = implode(".", $labels);
        $this->responseCounter += $count;
        return $domain;
    }

    function getDomainLabels($offset, &$counter = 0): array
    {
        $labels = [];
        $offsetStart = $offset;
        $return = false;
        while (!$return) {
            $labelLength = ord($this->readResponse(1, $offset++));
            if ($labelLength <= 0) {
                $return = true;
            } // end of data
            else if ($labelLength < 64) // uncompressed data
            {
                $labels[] = $this->readResponse($labelLength, $offset);
                $offset += $labelLength;
            } else // labelLength >= 64 --> pointer
            {
                $nextItem = $this->readResponse(1, $offset++);
                $pointerOffset = (($labelLength & 0x3f) << 8) + ord($nextItem);
                $labelsPointers = $this->getDomainLabels($pointerOffset);
                foreach ($labelsPointers as $labelPointer) {
                    $labels[] = $labelPointer;
                }
                $return = true;
            }
        }
        $counter = $offset - $offsetStart;

        return array_map(function ($item) {
            return str_replace('.', '\.', $item);
        }, $labels);
    }

    /**
     * @throws DnsHandlerException
     */
    function readRecord(): array
    {

        $domain = $this->getDomainLabel();

        // 10 byte header
        $headerResponse = $this->readResponse(10);

        $headerLen = strlen($headerResponse);
        if ($headerLen < 10) {
            throw new DnsHandlerException(
                'Unable to parse header data, it\'s length must be 10, got: ' .
                $headerLen . ' bytes, label: ' . json_encode($domain),
                DnsHandlerException::ERR_INVALID_RECORD_HEADER_LENGTH
            );
        }

        $headerData = unpack("ntype/nclass/Nttl/nlength", $headerResponse);

        $typeId = $headerData['type'];
        $typeName = RecordTypes::getName($typeId);

        $result = [
            'host'  => strtolower($domain),
            'ttl'   => $headerData['ttl'],
            'class' => RawClassTypes::getClassNameByRawType($headerData['class']),
            'type'  => $typeName,
        ];

        switch ($typeId) {

            case RecordTypes::A:
                $ipBinary = $this->readResponse(4);
                if (function_exists('inet_ntop')) {
                    $ip = inet_ntop($ipBinary);
                } else {
                    $ip = implode(".", unpack("Ca/Cb/Cc/Cd", $ipBinary));
                }
                $result['ip'] = $ip;
                break;

            case RecordTypes::AAAA:
                $ipBinary = $this->readResponse(16);
                if (function_exists('inet_ntop')) {
                    $ip = inet_ntop($ipBinary);
                } else {
                    $ip = implode(":", unpack("H4a/H4b/H4c/H4d/H4e/H4f/H4g/H4h", $ipBinary));
                }
                $result['ipv6'] = DnsUtils::ipV6Shortener($ip);
                break;

            case RecordTypes::CNAME:
            case RecordTypes::PTR:
            case RecordTypes::NS:
                $result['target'] = strtolower($this->getDomainLabel());
                break;

            case RecordTypes::DNSKEY:
            case RecordTypes::CDNSKEY:
                $values = unpack("nflags/cprotocol/calgo", $this->readResponse(4));
                $result['flags'] = $values['flags'];
                $result['protocol'] = (int)$values['protocol'];
                $result['algorithm'] = $values['algo'];
                $result['public-key'] =
                    DnsUtils::getSplitSignature(base64_encode($this->readResponse($headerData['length'] - 4)), 56);
                break;

            case RecordTypes::MX:
                $values = unpack("npri", $this->readResponse(2));
                $result['pri'] = $values['pri'];
                $result['target'] = strtolower($this->getDomainLabel());
                break;

            case RecordTypes::SOA:
                $values = $this->getDomainLabel();
                $responsible = $this->getDomainLabel();
                $buffer = $this->readResponse(20);
                $resultData = unpack("Nserial/Nrefresh/Nretry/Nexpire/Nminttl", $buffer); // butfix to NNNNN from nnNNN for 1.01
                $result['mname'] = strtolower($values);
                $result['rname'] = strtolower($responsible);
                $result['serial'] = $resultData['serial'];
                $result['refresh'] = $resultData['refresh'];
                $result['retry'] = $resultData['retry'];
                $result['expire'] = $resultData['expire'];
                $result['minimum-ttl'] = $resultData['minttl'];
                break;

            case RecordTypes::SRV:
                $response = $this->readResponse(6);
                $values = unpack("npriority/nweight/nport", $response);
                $result['pri'] = $values['priority'];
                $result['weight'] = $values['weight'];
                $result['port'] = $values['port'];
                $result['target'] = strtolower($this->getDomainLabel());
                break;

            case RecordTypes::TXT:
                $strLen = ord($this->readResponse());
                $text = $this->readResponse($strLen);
                $result['txt'] = DnsUtils::sanitizeRecordTxt($text);
                break;

            case RecordTypes::DEPRECATED_SPF:
                $result['type'] = 'TXT';
                $strLen = ord($this->readResponse());
                $result['txt'] = DnsUtils::sanitizeRecordTxt($this->readResponse($strLen));
                break;

            case RecordTypes::CAA:
                $values = $this->readResponse();
                $values = unpack("Cflags", $values);
                $strLen = ord($this->readResponse(1));
                $tags = $this->readResponse($strLen);
                $dif = $headerData['length'] - 2 - strlen($tags);
                $value = $this->readResponse($dif);
                $result['flags'] = $values['flags'];
                $result['tag'] = $tags;
                $result['value'] = $value;
                break;

            case RecordTypes::DS:
            case RecordTypes::CDS:
                $response = $this->readResponse($headerData['length']);
                $values = unpack("ntag/calgo/ctype/H*digest",
                    $response
                );

                $result['key-tag'] = is_numeric($values['tag']) ? (int)$values['tag'] : null;
                $result['algorithm'] = is_numeric($values['algo']) ? (int)$values['algo'] : null;
                $result['algorithm-digest'] = is_numeric($values['type']) ? (int)$values['type'] : null;
                $fullDigest = strtoupper($values['digest']);
                $result['digest'] = DnsUtils::getSplitSignature($fullDigest, 56);
                break;

            case RecordTypes::RRSIG:
                $readResponse = $this->readResponse($headerData['length']);
                $values = unpack("ntype/calgo/clabels/Nttl/Nexpire/Ninception/ntag",
                    $readResponse
                );

                $readResponseLen = strLen($readResponse); // 12 = original size

                $lastOffset = 0;
                $label = implode('.', $this->getConsecutiveLabels(substr($readResponse, 18), $lastOffset));
                $newOffset = 18 + $lastOffset;
                $signature = base64_encode(substr($readResponse, -($readResponseLen - $newOffset)));

                $result['type-covered'] = RecordTypes::getName($values['type']);
                $result['algorithm'] = $values['algo'];
                $result['labels-number'] = $values['labels'];
                $result['original-ttl'] = $values['ttl'];
                $result['signature-expiration'] = DnsUtils::getHumanReadableDateTime($values['expire']);
                $result['signature-creation'] = DnsUtils::getHumanReadableDateTime($values['inception']);
                $result['key-tag'] = $values['tag'];
                $result['signer-name'] = $label;
                $result['signature'] = DnsUtils::getSplitSignature($signature, 56);
                break;

            case RecordTypes::NSEC:
                $response = $this->readResponse($headerData['length']);
                $responseLen = strlen($response);
                $lastOffset = 0;
                $label = implode('.', $this->getConsecutiveLabels($response, $lastOffset));
                $blocksString = substr($response, -$responseLen + $lastOffset + 1);
                $blocks = $this->getBlocks($blocksString);
                $recordTypes = '';

                foreach ($blocks as $key => $block) {
                    $blockOffset = $key * 256;
                    $blockData = DnsUtils::getBitsFromString($block);
                    $typesFound = DnsUtils::getRecordsNamesFromBinary($blockData, $blockOffset);
                    $recordTypes .=
                        empty($recordTypes) ? $typesFound : ' ' . $typesFound;
                }

                $result['next-authoritative-name'] = $label;
                $result['types'] = $recordTypes;
                break;

            case RecordTypes::NSEC3PARAM:
                $response = $this->readResponse($headerData['length']);
                $values = unpack("Calgo/nflags/citerations/clength/H*salt",
                    $response
                );
                $result['algorithm'] = $values['algo'];
                $result['flags'] = $values['flags'];
                $result['iterations'] = $values['iterations'];
                $salt = strtoupper($values['salt']);
                $result['salt'] = $salt === '' ? '-' : $salt;
                break;

            case RecordTypes::HTTPS:
                $response = $this->readResponse($headerData['length']);
                $values = unpack('H*data', $response);
                $rawData = $values['data'];
                $originalLen = strlen($response);
                $result['separator'] = '\#';
                $result['original-length'] = $originalLen;
                $result['data'] = strtoupper(DnsUtils::getSplitSignature($rawData, 56));
                break;

            case RecordTypes::NAPTR:
                $response = $this->readResponse($headerData['length']);
                $values = unpack('norder/npreference', $response);
                $lastOffset = 4;
                $newOffset = 0;

                $labels = $this->getConsecutiveLabels($response, $newOffset, $lastOffset, 3);

                $flag = $labels[0] ?? '';
                $service = $labels[1] ?? '';
                $regexp = $labels[2] ?? '';
                $replacement = $labels[3] ?? '';
                $labelsLen = count($labels);

                if ($labelsLen > 3) {
                    array_shift($labels);
                    array_shift($labels);
                    array_shift($labels);
                    $replacement = implode('.', $labels);
                }

                $result['order'] = $values['order'];
                $result['pref'] = $values['preference'];
                $result['flag'] = $flag;
                $result['services'] = $service;
                $result['regex'] = $regexp;
                $result['replacement'] = $replacement;
                break;

            default:
                throw new DnsHandlerException(
                    'Not implemented type: ' . json_encode($typeId) . PHP_EOL .
                    ' headerData:' . json_encode($headerData),
                    DnsHandlerException::TYPE_ID_NOT_IMPLEMENTED
                );

        }

        $this->lastResult = $result;
        $this->lastIndex++;

        return $result;
    }

    private function getConsecutiveLabels(string $text, int &$i, int $startsFrom = 0, $count = 1): array
    {
        if (empty($text)) {
            return [];
        }

        $textLen = strlen($text);

        $foundCount = 0;

        $result = [];

        for ($i = $startsFrom; $i < $textLen; $i++) {
            $len = ord($text[$i]);
            if ($len === 0) {
                if ($foundCount >= $count) {
                    $i += 1;
                    break;
                }
            }

            $substr = substr($text, $i + 1, $len);

            if ($substr === chr(0) && $count === 1) {
                $substr = '\000';
            }

            $result[] = $substr;
            $i += $len;
            $foundCount++;
        }

        return $result;
    }

    /**
     * @throws DnsHandlerException
     */
    private function readAnswers(): array
    {
        if (empty($this->rawResponse)) {
            return [];
        }

        $answersCount = $this->getHeaderAnswersCount();

        if ($answersCount === 0) {
            return [];
        }

        $result = [];

        for ($index = 0; $index < $answersCount; $index++) {
            $record = $this->readRecord();
            if (!empty($record)) {
                $result [] = $record;
            }
        }

        return $result;
    }

    private function readQuestions(): array
    {
        if (empty($this->rawResponse)) {
            return [];
        }

        $questionsCount = $this->getHeaderQuestionsCount();

        if ($questionsCount === 0) {
            return [];
        }

        do {
            $byteValue = ord($this->readResponse(1));
        } while ($byteValue != 0);

        return [$this->readResponse(4)];
    }

    /**
     * @throws DnsHandlerException
     */
    public function getData(): array
    {
        $this->questions = $this->readQuestions();
        $this->answers = $this->readAnswers();
        return $this->answers;
    }

    /**
     * @return int
     */
    private function headerIsTruncated(): int
    {
        return ($this->headerData['spec'] >> 9) & 1;
    }

    private function getBlocks(string $string): array
    {

        if (empty($string)) {
            return [];
        }

        $result = [];
        $stringLen = strlen($string);

        for ($i = 0; $i < $stringLen; $i++) {
            $item = substr($string, $i, 1);
            $len = ord($item);

            if ($len === 0) {
                break;
            }

            $result[] = substr($string, $i + 1, $len);
            $i += $len + 1;
        }

        return $result;
    }

}

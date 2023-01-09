<?php

namespace MamaOmida\Dns\Handlers\Types;

use MamaOmida\Dns\Handlers\AbstractDnsHandler;
use MamaOmida\Dns\Handlers\DnsHandlerException;
use MamaOmida\Dns\Handlers\DnsHandlerTypes;
use MamaOmida\Dns\Records\DnsRecordProperties;
use MamaOmida\Dns\Records\RecordTypes;
use MamaOmida\Dns\Records\DnsUtils;
use MamaOmida\Dns\Regex;

class Dig extends AbstractDnsHandler
{

    public function getType(): string
    {
        return DnsHandlerTypes::DIG;
    }

    /**
     * @throws DnsHandlerException
     */
    public function canUseIt(): bool
    {
        $result = $this->executeCommand('dig -v 2>&1');
        return !empty($result[0]) && stripos($result[0], 'dig') === 0;
    }

    /**
     * @throws DnsHandlerException
     */
    public function getDnsData(string $hostName, int $typeId): array
    {
        $this->validateParams($hostName, $typeId);

        return $this->normalizeRawResult(
            $this->getDnsRawResult($hostName, $typeId)
        );
    }

    /**
     * @throws DnsHandlerException
     */
    public function getDnsRawResult(string $hostName, int $typeId): array
    {

        $command = $this->getCommand($hostName, $typeId);

        if (is_null($command)) {
            return [];
        }

        if (!$this->isValidCommand($command)) {
            return [];
        }

        $output = $this->executeCommand($command);

        return array_filter($output);
    }

    protected function getCommand(string $hostName, int $typeId): ?string
    {
        try {
            $this->validateParams($hostName, $typeId);
        } catch (DnsHandlerException $e) {
            return null;
        }

        $recordName = RecordTypes::getName($typeId);


        if (is_null($recordName)) {
            return null;
        }

        $result = 'dig +nocmd +bufsize=1024 +noall +noauthority +answer +nomultiline +tries=' . ($this->retries + 1) . ' +time=' . $this->timeout;
        $result .= ' ' . $hostName . ' ' . $recordName;

        return $result . (empty($this->nameserver) ? '' : ' @' . $this->nameserver);
    }

    /**
     * @throws DnsHandlerException
     */
    protected function executeCommand(string $command): array
    {
        $result = exec($command, $output);

        if (!$this->isValidOutput($output)) {
            throw new DnsHandlerException(
                'Error: ' . json_encode($output) . PHP_EOL .
                ' Command: ' . PHP_EOL . json_encode($command),
                DnsHandlerException::ERR_UNABLE_TO_GET_RECORD
            );
        }

        return $result === false ? [] : $output;
    }

    public function isValidCommand(string $command): bool
    {
        return preg_match(Regex::DIG_COMMAND, $command) === 1;
    }

    public function getPropertiesData(int $typeId): ?array
    {
        $properties = DnsRecordProperties::getProperties($typeId);
        if (empty($properties)) {
            return null;
        }
        return array_merge(DnsRecordProperties::getDefaultProperties(), $properties);
    }

    /**
     * @throws DnsHandlerException
     */
    private function normalizeRawResult(array $rawResult): array
    {
        if (empty($rawResult)) {
            return [];
        }

        $result = [];

        foreach ($rawResult as $rawLine) {

            if (strpos($rawLine, ';;') === 0) {
                return [];
            }

            $lineData = $this->lineToArray($rawLine, 5);
            $type = $lineData[3] ?? null;
            $typeId = RecordTypes::getType($type);

            if (is_null($typeId)) {
                continue;
            }

            $configData = $this->getPropertiesData($typeId);

            if (!empty($configData)) {
                $result[] = $this->getRawData($configData, $rawLine);
            } else {
                throw new DnsHandlerException(
                    'Config data not found for line: ' . json_encode($rawLine),
                    DnsHandlerException::ERR_CONFIG_DATA_NOT_FOUND
                );
            }
        }

        return $result;
    }

    private function getRawData(array $configData, string $rawLine): ?array
    {

        $array = $this->lineToArray($rawLine, count($configData));

        $result = [];

        foreach ($array as $key => $value) {

            $propertyName = $configData[$key];

            $value = $this->getFormattedPropertyValue($propertyName, $value);

            $result[$propertyName] = $value;
        }

        if (isset($result['txt'])) {
            $result['txt'] = ltrim(rtrim($result['txt'], '"'), '"');
        }

        return $result;
    }

    private function isValidOutput(array $output): bool
    {
        return empty($output)
            || strpos($output[0], ';;') !== 0;
    }

    /**
     * @param $propertyName
     * @param $value
     * @return int|mixed|string
     */
    private function getFormattedPropertyValue($propertyName, $value)
    {
        if (
            in_array(
                $propertyName,
                ['host', 'mname', 'rname', 'target', 'signer-name', 'next-authoritative-name', 'replacement'
                ])) {
            $value = strtolower(rtrim($value, '.'));
        }

        if (in_array($propertyName, ['value', 'flag', 'services', 'regex'])) {
            $value = trim($value, '"');
        }

        if ($propertyName === 'ipv6') {
            $value = DnsUtils::ipV6Shortener($value);
        }

        if ($propertyName === 'type' && $value === 'SPF') {
            $value = 'TXT';
        }

        $value = DnsRecordProperties::isNumberProperty($propertyName)
            ? $value + 0
            : $value;

        return DnsRecordProperties::isLoweredCaseProperty($propertyName)
            ? strtolower($value)
            : $value;
    }

}

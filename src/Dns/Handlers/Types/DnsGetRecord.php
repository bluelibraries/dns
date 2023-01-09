<?php

namespace MamaOmida\Dns\Handlers\Types;

use Exception;
use MamaOmida\Dns\Handlers\AbstractDnsHandler;
use MamaOmida\Dns\Handlers\DnsHandlerException;
use MamaOmida\Dns\Handlers\DnsHandlerTypes;
use MamaOmida\Dns\Records\RecordTypes;

class DnsGetRecord extends AbstractDnsHandler
{

    private static array $internalPHPTypes = [
        RecordTypes::A     => DNS_A,
        RecordTypes::CNAME => DNS_CNAME,
        RecordTypes::HINFO => DNS_HINFO,
        RecordTypes::CAA   => DNS_CAA,
        RecordTypes::MX    => DNS_MX,
        RecordTypes::NS    => DNS_NS,
        RecordTypes::PTR   => DNS_PTR,
        RecordTypes::SOA   => DNS_SOA,
        RecordTypes::TXT   => DNS_TXT,
        RecordTypes::AAAA  => DNS_AAAA,
        RecordTypes::SRV   => DNS_SRV,
        RecordTypes::NAPTR => DNS_NAPTR,
        RecordTypes::A6    => DNS_A6,
        RecordTypes::ALL   => DNS_ALL,
    ];

    private static function getInternalTypeId(int $typeId): ?int
    {
        return static::$internalPHPTypes[$typeId] ?? null;
    }

    public function getType(): string
    {
        return DnsHandlerTypes::DNS_GET_RECORD;
    }

    public function canUseIt(): bool
    {
        return function_exists('dns_get_record');
    }

    /**
     * @throws DnsHandlerException
     */
    public function getDnsData(string $hostName, int $typeId): array
    {
        $this->validateParams($hostName, $typeId);
        $this->validatePHPInternalTypeId($typeId);

        $internalTypeId = static::getInternalTypeId($typeId);

        if ($typeId < 0) {
            return [];
        }

        try {
            return $this->getDnsRawResult($hostName, $internalTypeId);
        } catch (Exception $e) {
            throw new DnsHandlerException(
                'Unable to get data, `dns_get_record` error: ' . $e->getMessage() .
                ' hostName: ' . json_encode($hostName) . ' , typeName: ' .
                json_encode(RecordTypes::getName($typeId)) .
                ' , internal typeId:' . json_encode($internalTypeId),
                DnsHandlerException::ERR_DNS_GET_RECORD_DATA_EXCEPTION
            );
        }
    }

    public function getDnsRawResult(string $hostName, int $type): array
    {
        $startProcess = time();
        for ($i = 0; $i <= $this->retries; $i++) {
            if (
                ($result = $this->getDnsRecord($hostName, $type)) !== []
                || ((time() - $startProcess) >= $this->timeout)
            ) {
                return is_array($result) ? $result : [];
            }
        }
        return [];
    }

    /**
     * @param string $hostName
     * @param int $type
     * @return array|bool
     */
    protected function getDnsRecord(string $hostName, int $type)
    {
        return empty($hostName) ? false : $this->getUpdatedRecordsData(dns_get_record($hostName, $type));
    }

    private function getUpdatedRecordsData($records): array
    {
        if (!is_array($records) || empty($records)) {
            return $records;
        }
        foreach ($records as $key => $record) {
            if ($record['type'] === 'NAPTR') {
                $records[$key] = $this->fixNAPTRFlags($record);
            }
        }
        return $records;
    }

    private function fixNAPTRFlags(array $record): array
    {
        $result = [];
        foreach ($record as $key => $value) {
            if ($key === 'flags') {
                $result['flag'] = $value;
            } else {
                $result[$key] = $value;
            }
        }
        return $result;
    }

    /**
     * @throws DnsHandlerException
     */
    public function setNameserver(?string $nameserver): self
    {
        throw new DnsHandlerException(
            'Unable to set nameserver, as `dns_get_record` cannot use custom nameservers!',
            DnsHandlerException::UNABLE_TO_USE_CUSTOM_NAMESERVER
        );
    }

    /**
     * @param int $typeId
     * @return void
     * @throws DnsHandlerException
     */
    private function validatePHPInternalTypeId(int $typeId): void
    {
        if (!isset(self::$internalPHPTypes[$typeId])) {
            $recordTypeName = RecordTypes::getName($typeId);
            throw new DnsHandlerException(
                'DNS record type ' . json_encode($recordTypeName) .
                ' , please use a different DNS Handler (TCP is recommended)!',
                DnsHandlerException::TYPE_ID_NOT_SUPPORTED
            );
        }
    }

}

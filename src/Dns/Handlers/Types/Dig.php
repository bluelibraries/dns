<?php

namespace MamaOmida\Dns\Handlers\Types;

use MamaOmida\Dns\Handlers\AbstractDnsHandler;
use MamaOmida\Dns\Handlers\DnsHandlerException;
use MamaOmida\Dns\Records\DnsRecordTypes;

class Dig extends AbstractDnsHandler
{

    protected static array $defaultProperties = ['host', 'ttl', 'class', 'type'];

    protected static array $properties = [
        DnsRecordTypes::A     => ['ip'],
        DnsRecordTypes::AAAA  => ['ipv6'],
        DnsRecordTypes::CAA   => ['flags', 'tag', 'value'],
        DnsRecordTypes::CNAME => ['target'],
        DnsRecordTypes::SOA   => ['mname', 'rname', 'serial', 'refresh', 'retry', 'expire', 'minimum-ttl'],
        DnsRecordTypes::TXT   => ['txt'],
        DnsRecordTypes::NS    => ['target'],
        DnsRecordTypes::MX    => ['pri', 'target'],
        DnsRecordTypes::PTR   => ['target'],
        DnsRecordTypes::SRV   => ['pri', 'weight', 'port', 'target'],
    ];

    /**
     * @throws DnsHandlerException
     */
    public function getDnsData(string $hostName, int $type): array
    {
        $this->validateParams($hostName, $type);

        return $this->normalizeRawResult(
            $this->getDnsRawResult($hostName, $type)
        );
    }

    private function getDnsRawResult($hostName, $type): array
    {
        $command = $this->getCommand($hostName, $type);
        exec($command, $output, $resultCode);
        return array_filter($output);
    }

    private function getCommand(string $hostName, int $type): string
    {
        $result = 'dig +nocmd +noall +authority +answer +nomultiline +tries=3 +time=' . $this->timeout;
        return $result . ' ' . $hostName . ' ' . DnsRecordTypes::getName($type).' @8.8.8.8';
    }

    public function getPropertiesData($typeId): ?array
    {
        if (empty(self::$properties[$typeId])) {
            return null;
        }
        return array_merge(self::$defaultProperties, self::$properties[$typeId]);
    }

    private function normalizeRawResult(array $rawResult): array
    {
        if (empty($rawResult)) {
            return [];
        }

        $result = [];

        foreach ($rawResult as $rawLine) {
            $lineData = $this->lineToArray($rawLine, 5);
            $type = $lineData[3] ?? null;
            $typeId = DnsRecordTypes::getType($type);
            $configData = $this->getPropertiesData($typeId);
            if (!empty($configData)) {
                $result[] = $this->getRawData($configData, $rawLine);
            }
        }

        return $result;
    }

    private function getRawData(array $configData, string $rawLine): ?array
    {
        if (empty($rawLine) || ($configDataLen = count($configData)) < 4) {
            return null;
        }

        $array = $this->lineToArray($rawLine, $configDataLen);

        $result = [];

        foreach ($array as $key => $value) {
            $result[$configData[$key]] = $value;
        }

        return $result;
    }

}
<?php

namespace MamaOmida\Dns\Handlers\Types;

use MamaOmida\Dns\Handlers\AbstractDnsHandler;
use MamaOmida\Dns\Handlers\DnsHandlerException;
use MamaOmida\Dns\Records\DnsRecordProperties;
use MamaOmida\Dns\Records\DnsRecordTypes;

class Dig extends AbstractDnsHandler
{

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

    public function getDnsRawResult($hostName, $type): array
    {
        $command = $this->getCommand($hostName, $type);

        if (is_null($command)) {
            return [];
        }

        if (!$this->isValidCommand($command)) {
            return [];
        }

        $output = $this->executeCommand($command);

        return array_filter($output);
    }

    protected function getCommand(string $hostName, int $type): ?string
    {
        try {
            $this->validateParams($hostName, $type);
        } catch (DnsHandlerException $e) {
            return null;
        }

        $recordName = DnsRecordTypes::getName($type);

        if (is_null($recordName)) {
            return null;
        }

        $result = 'dig +nocmd +noall +authority +answer +nomultiline +tries=' . ($this->retries + 1) . ' +time=' . $this->timeout;

        return $result . ' ' . $hostName . ' ' . $recordName;//. ' @8.8.8.8';
    }

    protected function executeCommand(string $command): array
    {
        $result = exec($command, $output);
        return $result === false ? [] : $output;
    }

    public function isValidCommand(string $command)
    {
        return preg_match('/dig \+nocmd \+noall \+authority \+answer \+nomultiline \+tries=\d+ \+time=\d+ ([a-z0-9.\-_]+) ([A-Z]+)/i', $command);
    }

    public function canUseDig(): bool
    {
        $result = $this->executeCommand('dig -v 2>&1');
        return !empty($result[0]) && stripos($result[0], 'dig') === 0;
    }

    public function getPropertiesData(int $typeId): ?array
    {
        $properties = DnsRecordProperties::getProperties($typeId);
        if (empty($properties)) {
            return null;
        }
        return array_merge(DnsRecordProperties::getDefaultProperties(), $properties);
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

        $array = $this->lineToArray($rawLine, count($configData));

        $result = [];

        foreach ($array as $key => $value) {
            $propertyName = $configData[$key];
            $result[$propertyName] = DnsRecordProperties::isNumberProperty($propertyName)
                ? $value + 0
                : $value;
        }

        return $result;
    }

}
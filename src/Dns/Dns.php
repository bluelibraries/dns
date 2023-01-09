<?php

namespace MamaOmida\Dns;

use MamaOmida\Dns\Handlers\DnsHandlerException;
use MamaOmida\Dns\Handlers\DnsHandlerInterface;
use MamaOmida\Dns\Handlers\Types\DnsGetRecord;
use MamaOmida\Dns\Records\RecordException;
use MamaOmida\Dns\Records\RecordFactory;
use MamaOmida\Dns\Records\RecordInterface;

class Dns
{
    private DnsHandlerInterface $handler;
    private RecordFactory $factory;

    /**
     * @param DnsHandlerInterface|null $handler
     * @param RecordFactory|null $factory
     */
    public function __construct(DnsHandlerInterface $handler = null, RecordFactory $factory = null)
    {
        if (is_null($handler)) {
            $handler = new DnsGetRecord();
        }

        if (is_null($factory)) {
            $factory = new RecordFactory();
        }

        $this->handler = $handler;
        $this->factory = $factory;
    }

    /**
     * @return DnsHandlerInterface
     */
    public function getHandler(): DnsHandlerInterface
    {
        return $this->handler;
    }

    /**
     * @param DnsHandlerInterface $handler
     * @return Dns
     */
    public function setHandler(DnsHandlerInterface $handler): Dns
    {
        $this->handler = $handler;
        return $this;
    }

    /**
     * @return RecordFactory
     */
    public function getFactory(): RecordFactory
    {
        return $this->factory;
    }

    /**
     * @param RecordFactory $factory
     * @return Dns
     */
    public function setFactory(RecordFactory $factory): Dns
    {
        $this->factory = $factory;
        return $this;
    }

    /**
     * @param string $hostName
     * @param int|array $type
     * @param bool $extendedRecords
     * @param bool $keepOrder
     * @param bool $removeDuplicates
     * @return array
     * @throws DnsHandlerException
     * @throws RecordException
     */
    public function getRecords(string $hostName, $type, bool $extendedRecords = false, bool $keepOrder = true, bool $removeDuplicates = true): array
    {
        if (is_int($type)) {
            return $this->getRecordDataForType($hostName, $type, $extendedRecords, $keepOrder);
        }

        $result = [];

        foreach ($type as $typeId) {
            $result = array_merge($result, $this->getRecordDataForType($hostName, $typeId, $extendedRecords, $keepOrder));
        }

        if ($removeDuplicates) {
            $result = $this->removeDuplicates($result);
        }

        return $result;
    }

    /**
     * @param RecordInterface[] $results
     * @return RecordInterface[]
     */
    private function sortRecords(array $results): array
    {

        if (empty($results)) {
            return [];
        }

        $result = [];

        foreach ($results as $record) {
            $result[$record->getHash()] = $record;
        }

        ksort($result);

        return array_values($result);
    }

    /**
     * @param string $hostName
     * @param $typeId
     * @param bool $extendedRecords
     * @param bool $keepOrder
     * @return array|RecordInterface[]
     * @throws DnsHandlerException
     * @throws RecordException
     */
    private function getRecordDataForType(string $hostName, $typeId, bool $extendedRecords, bool $keepOrder): array
    {
        $recordsData = $this->handler->getDnsData($hostName, $typeId);

        if (empty($recordsData)) {
            return [];
        }

        $result = [];

        foreach ($recordsData as $recordData) {
            $result[] = $this->factory->create($recordData, $extendedRecords);
        }

        if ($keepOrder) {
            $result = $this->sortRecords($result);
        }

        return $result;
    }

    /**
     * @param RecordInterface[] $array
     * @return void
     */
    private function removeDuplicates(array $array): array
    {
        if (empty($array)) {
            return [];
        }
        $result = [];

        $foundHashes = [];

        foreach ($array as $record) {
            $recordHash = $record->getHash();
            if (!in_array($recordHash, $foundHashes)) {
                $result[] = $record;
                $foundHashes[] = $recordHash;
            }
        }

        return $result;
    }

}

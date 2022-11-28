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
     * @param int|null $type
     * @return RecordInterface[]
     * @throws RecordException
     * @throws DnsHandlerException
     */
    public function getRecords(string $hostName, ?int $type = DNS_ALL): array
    {
        $recordsData = $this->handler->getDnsData($hostName, $type);

        if (empty($recordsData)) {
            return [];
        }

        $result = [];

        foreach ($recordsData as $recordData) {
            $result[] = $this->factory->create($recordData);
        }

        return $result;
    }

}

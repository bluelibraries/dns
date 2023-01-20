<?php

namespace Integration\Facade;

use MamaOmida\Dns\Facade\DNS;
use MamaOmida\Dns\Handlers\DnsHandlerException;
use MamaOmida\Dns\Handlers\DnsHandlerFactoryException;
use MamaOmida\Dns\Handlers\DnsHandlerTypes;
use MamaOmida\Dns\Records\RecordException;
use MamaOmida\Dns\Records\RecordTypes;
use PHPUnit\Framework\TestCase;

class DNSTest extends TestCase
{

    public function getRecordsDataProvider(): array
    {
        return [
            ['', [], []],
            ['test.com', RecordTypes::TXT],
            ['google.com', [RecordTypes::A]],
            ['test.com', [RecordTypes::NS]],
        ];
    }

    /**
     * @param string $host
     * @param int|int[] $types
     * @return void
     * @throws DnsHandlerException
     * @throws DnsHandlerFactoryException
     * @throws RecordException
     * @dataProvider getRecordsDataProvider
     */
    public function testGetRecords(string $host, $types)
    {
        static::assertIsArray(DNS::getRecords($host, $types, DnsHandlerTypes::TCP, true, '8.8.8.8'));
    }

}

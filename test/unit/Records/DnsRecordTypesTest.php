<?php

namespace MamaOmidaTest\Dns\Unit\Records;

use MamaOmida\Dns\Records\DnsRecordTypes;
use PHPUnit\Framework\TestCase;

class DnsRecordTypesTest extends TestCase
{

    public static function testGetNameInvalid()
    {
        static::assertNull(DnsRecordTypes::getName(0));
    }

    public static function validTypesDataProvider(): array
    {
        return [
            [DnsRecordTypes::A],
            [DnsRecordTypes::CNAME],
            [DnsRecordTypes::HINFO],
            [DnsRecordTypes::CAA],
            [DnsRecordTypes::MX],
            [DnsRecordTypes::NS],
            [DnsRecordTypes::PTR],
            [DnsRecordTypes::SOA],
            [DnsRecordTypes::TXT],
            [DnsRecordTypes::AAAA],
            [DnsRecordTypes::SRV],
            [DnsRecordTypes::NAPTR],
            [DnsRecordTypes::A6],
            [DnsRecordTypes::ALL],
            [DnsRecordTypes::ANY]
        ];
    }

    /**
     * @return void
     * @dataProvider validTypesDataProvider
     */
    public static function testGetNameValid(int $type)
    {
        static::assertIsString(DnsRecordTypes::getName($type));
    }

}
<?php

namespace Unit\Records;

use BlueLibraries\Dns\Records\DnsUtils;
use PHPUnit\Framework\TestCase;

class DnsUtilsTest extends TestCase
{

    public function isValidDomainOrSubdomainDataProvider(): array
    {
        return [
            ['', false],
            ['a', false],
            ['a.r', false],
            ['a.ro', true],
            ['a.com', true],
            ['mamaomida.test.com', true],
            ['other.mamaomida.test.com', true],
            ['another.other.mamaomida.test.com', true],
        ];
    }

    /**
     * @param string $domain
     * @param bool $expected
     * @dataProvider isValidDomainOrSubdomainDataProvider
     * @return void
     */
    public function testIsValidDomainOrSubdomain(string $domain, bool $expected)
    {
        static::assertSame(DnsUtils::isValidDomainOrSubdomain($domain), $expected);
    }

    public function ipV6ShortenerDataProvider(): array
    {
        return [
            ['', ''],
            ['::ffff:93.113.174.110', '::ffff:93.113.174.110'],
            ['2041:0000:0000::875b:0', '2041:0000:0000::875b:0'],
            ['2041:0000:140F::875B:131B', '2041:0:140F::875B:131B'],
            ['2041:0001:140f::875b:131b', '2041:1:140f::875b:131b'],
            ['2041:22:140f::875b:131b', '2041:22:140f::875b:131b'],
        ];
    }

    /**
     * @param string $ipv6
     * @param string $expected
     * @dataProvider ipV6ShortenerDataProvider
     * @return void
     */
    public function testIpV6Shortener(string $ipv6, string $expected)
    {
        static::assertSame(DnsUtils::ipV6Shortener($ipv6), $expected);
    }

    public function sanitizeRecordTxtDataProvider(): array
    {
        return [
            ['', ''],
            ['ana are mere', 'ana are mere'],
            ['"ana are mere', '\"ana are mere'],
            ['mama @564"23434"cs\'\'=', 'mama @564\"23434\"cs\'\'=']
        ];
    }

    /**
     * @dataProvider sanitizeRecordTxtDataProvider
     * @return void
     */
    public function testSanitizeRecordTxt(string $value, string $expected)
    {
        static::assertSame(DnsUtils::sanitizeRecordTxt($value), $expected);
    }

    public function getBitsFromStringDataProvider(): array
    {
        return [
            ['', []],
            ['A', ['0', '1', '0', '0', '0', '0', '0', '1']],
            [chr(1), ['0', '0', '0', '0', '0', '0', '0', '1']],
            [chr(255), ['1', '1', '1', '1', '1', '1', '1', '1']],
        ];
    }

    /**
     * @param $string
     * @param $expected
     * @dataProvider getBitsFromStringDataProvider
     * @return void
     */
    public function testGetBitsFromString($string, $expected)
    {
        static::assertSame(DnsUtils::getBitsFromString($string), $expected);
    }

    public function getRecordsNamesFromBinaryDataProvider(): array
    {
        return [
            [[], 0, ''],
            [[0, 1, 1], 0, 'A NS'],
            [[1, 1, 1], 256, 'URI CAA AVC'],
        ];
    }

    /**
     * @param array $binary
     * @param int $offset
     * @param string $expected
     * @return void
     * @dataProvider getRecordsNamesFromBinaryDataProvider
     */
    public function testGetRecordsNamesFromBinary(array $binary, int $offset, string $expected)
    {
        static::assertSame(DnsUtils::getRecordsNamesFromBinary($binary, $offset), $expected);
    }

    public function getHumanReadableDateTimeDataProvider(): array
    {
        return [
            [0, 19700101000000],
            [1673468849, 20230111202729],
            [1673862849, 20230116095409],
        ];
    }

    /**
     * @param int $timestamp
     * @param int $expected
     * @dataProvider getHumanReadableDateTimeDataProvider
     * @return void
     */
    public function testGetHumanReadableDateTime(int $timestamp, int $expected)
    {
        static::assertSame(DnsUtils::getHumanReadableDateTime($timestamp), $expected);
    }

    public function getSplitSignatureDataProvider(): array
    {
        return [
            ['', 58, ' ', ''],
            ['x', 58, ' ', 'x'],
            ['1234', 1, ' ', '1 2 3 4'],
            ['1234567890123456789012', 10, ' ', '1234567890 1234567890 12'],
        ];
    }

    /**
     * @param string $signature
     * @param int $bufferLen
     * @param string $separator
     * @param string $expected
     * @dataProvider getSplitSignatureDataProvider
     * @return void
     */
    public function testGetSplitSignature(string $signature, int $bufferLen, string $separator, string $expected)
    {
        static::assertSame(DnsUtils::getSplitSignature($signature, $bufferLen, $separator), $expected);
    }

    public function asciiStringDataProvider(): array
    {
        return [
            ['', '', ''],
            ['', ' ', ''],
            ['A', '', '65'],
            ['A', ' ', '65'],
            ['AA', '', '6565'],
            ['AA', ' ', '65 65'],
        ];
    }

    /**
     * @param string $value
     * @param string $glue
     * @param string $expected
     * @return void
     * @dataProvider asciiStringDataProvider
     */
    public function testAsciiString(string $value, string $glue, string $expected)
    {
        self::assertSame($expected, DnsUtils::asciiString($value, $glue));
    }

    public function trimDataProvider(): array
    {
        return [
            ['', '' , 0, ''],
            ['test', 't', 1, 'es'],
            ['test', 't', 2, 'es'],
            ['test', 't', 0, 'test'],
            ['ttestt', 't', 0, 'ttestt'],
            ['ttestt', 't', 1, 'test'],
            ['ttestt', 't', 2, 'es'],
            ['ttestt', 't', 3, 'es'],
        ];
    }

    /**
     * @param string $haystack
     * @param string $needle
     * @param int $length
     * @param string $expected
     * @dataProvider trimDataProvider
     * @return void
     */
    public function testTrim(string $haystack, string $needle, int $length, string $expected)
    {
        self::assertSame($expected, DnsUtils::trim($haystack, $needle, $length));
    }

}

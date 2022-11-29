<?php

namespace MamaOmida\Dns\Test\Unit\Handlers\Types;

use MamaOmida\Dns\Handlers\DnsHandlerException;
use MamaOmida\Dns\Handlers\Types\DnsGetRecord;
use PHPUnit\Framework\TestCase;

class DnsGetRecordTest extends TestCase
{
    protected DnsGetRecord $subject;

    public function setUp(): void
    {
        parent::setUp();

        $this->subject = new DnsGetRecord();
    }

    public function testGetDnsDataEmptyHostName()
    {
        $this->expectException(DnsHandlerException::class);
        $this->expectExceptionMessage('Invalid hostname, it must not be empty!');
        $this->expectExceptionCode(DnsHandlerException::HOSTNAME_EMPTY);
        $this->subject->getDnsData('', DNS_ALL);
    }

    public function testGetDnsDataInvalidHostNameLength()
    {
        $this->expectException(DnsHandlerException::class);
        $this->expectExceptionMessage('Invalid hostname "fo" length. It must be 3 or more!');
        $this->expectExceptionCode(DnsHandlerException::HOSTNAME_LENGTH_TOO_SMALL);
        $this->subject->getDnsData('fo', DNS_ALL);
    }

    public function testGetDnsDataInvalidHostNameBadCharacters()
    {
        $this->expectException(DnsHandlerException::class);
        $this->expectExceptionMessage('Invalid hostname "ana*are*mere.com" format! (characters "A-Za-z0-9.-" allowed)');
        $this->expectExceptionCode(DnsHandlerException::HOSTNAME_FORMAT_INVALID);
        $this->subject->getDnsData('ana*are*mere.com', DNS_ALL);
    }

    public function testGetDnsDataHostNameFormatExceededLength()
    {
        $hostName = str_repeat('a', 250) . '.com';
        $this->expectException(DnsHandlerException::class);
        $this->expectExceptionMessage('Invalid hostname "' . $hostName . '" length! (min 3, max 253 characters allowed)');
        $this->expectExceptionCode(DnsHandlerException::HOSTNAME_LENGTH_INVALID);
        $this->subject->getDnsData($hostName, DNS_ALL);
    }

    public function testGetDnsDataHostTLDExtensionFormatExceededLength()
    {
        $hostName = 'a.' . str_repeat('b', 64);
        $this->expectException(DnsHandlerException::class);
        $this->expectExceptionMessage('Invalid hostname "' . $hostName . '" TLD (extension) length! (min 1, max 63 characters allowed)');
        $this->expectExceptionCode(DnsHandlerException::HOSTNAME_TLD_LENGTH_INVALID);
        $this->subject->getDnsData($hostName, DNS_ALL);
    }

    public function testGetDnsDataInvalidTypeId()
    {
        $this->expectException(DnsHandlerException::class);
        $this->expectExceptionMessage('Invalid records typeId: -1 !');
        $this->expectExceptionCode(DnsHandlerException::TYPE_ID_INVALID);
        $this->subject->getDnsData('test.com', -1);
    }

    public function testGetTimeout()
    {
        $this->assertSame(5, $this->subject->getTimeout());
    }

    public function testSetTimeout()
    {
        $this->subject->setTimeout(100);
        $this->assertSame(100, $this->subject->getTimeout());
    }

    public function testSetTimeoutSameObject()
    {
        $this->assertSame($this->subject, $this->subject->setTimeout(4));
    }

    public function testGetRetries()
    {
        $this->assertSame(5, $this->subject->getRetries());
    }

    public function testSetRetries()
    {
        $this->subject->setRetries(9);
        $this->assertSame(9, $this->subject->getRetries());
    }

    public function testSetRetriesSameObject()
    {
        $this->assertSame($this->subject, $this->subject->setRetries(3));
    }

    public function testLineToArrayEmptyLine()
    {
        $this->assertSame([], $this->subject->lineToArray('', 1));
    }

    public function testLineToArraySingleLine()
    {
        $this->assertSame(['Ana are mere'], $this->subject->lineToArray("Ana are \n mere", 1));
    }

    public function testLineToArrayMultilineLastLineKeepOtherData()
    {
        $this->assertSame(['Ana', 'are mere'], $this->subject->lineToArray("Ana are \n mere", 2));
    }

    public function testLineToArrayMultiline()
    {
        $this->assertSame(['Ana', 'are', 'mere'], $this->subject->lineToArray("Ana are \n mere", 10));
    }

}
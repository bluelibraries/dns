<?php

namespace MamaOmida\Dns\Test\Unit\Handlers\Types;

use MamaOmida\Dns\Handlers\DnsHandlerException;
use MamaOmida\Dns\Handlers\Types\Dig;
use MamaOmida\Dns\Records\DnsRecordTypes;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class DigTest extends TestCase
{
    /**
     * @var Dig|MockObject
     */
    protected $subject;

    public function setUp(): void
    {
        parent::setUp();
        $this->subject = $this->getMockBuilder(Dig::class)
            ->onlyMethods(['executeCommand'])
            ->getMock();
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
        $this->expectExceptionMessage('Invalid records typeId: 0 !');
        $this->expectExceptionCode(DnsHandlerException::TYPE_ID_INVALID);
        $this->subject->getDnsData('test.com', 0);
    }

    /**
     * @throws DnsHandlerException
     */
    public function testGetDnsDataEmptyData()
    {
        $this->setValueInExecuteCommand([]);
        $this->assertSame([], $this->subject->getDnsData('test.com', DNS_ALL));
    }

    /**
     * @throws DnsHandlerException
     */
    public function testGetDnsDataValidData()
    {
        $this->setValueInExecuteCommand(['test.com 0 IN A 20.81.111.85']);
        $this->assertSame(
            [
                [
                    'host'  => 'test.com',
                    'ttl'   => 0,
                    'class' => 'IN',
                    'type'  => 'A',
                    'ip'    => '20.81.111.85',
                ]
            ],
            $this->subject->getDnsData('test.com', DNS_ALL)
        );
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

    protected function setValueInExecuteCommand(array $value)
    {
        $this->subject->method('executeCommand')
            ->willReturn($value);
    }

    public function testGetDnsDataNotFoundResultMakesOnlyOneCall()
    {
        $this->setValueInExecuteCommand([]);
        $this->subject->expects(
            $this->once()
        )->method('executeCommand');
        $this->assertSame([], $this->subject->getDnsData('test.com', DNS_TXT));
    }

    public function testGetPropertiesDataNoDefinedProperties()
    {
        $this->assertNull($this->subject->getPropertiesData(0));
    }

    public function getPropertiesDataProvider(): array
    {
        return [
            [DnsRecordTypes::A, ['ip'],],
            [DnsRecordTypes::AAAA, ['ipv6'],],
            [DnsRecordTypes::CAA, ['flags', 'tag', 'value'],],
            [DnsRecordTypes::CNAME, ['target'],],
            [DnsRecordTypes::SOA, ['mname', 'rname', 'serial', 'refresh', 'retry', 'expire', 'minimum-ttl'],],
            [DnsRecordTypes::TXT, ['txt'],],
            [DnsRecordTypes::NS, ['target'],],
            [DnsRecordTypes::MX, ['pri', 'target'],],
            [DnsRecordTypes::PTR, ['target'],],
            [DnsRecordTypes::SRV, ['pri', 'weight', 'port', 'target'],],
        ];
    }

    /**
     * @param int $recordTypeId
     * @param array $additionalData
     * @return void
     * @dataProvider getPropertiesDataProvider
     */
    public function testGetPropertiesDataValid(int $recordTypeId, array $additionalData)
    {
        $finalData = array_merge(['host', 'ttl', 'class', 'type'], $additionalData);
        $this->assertSame($finalData, $this->subject->getPropertiesData($recordTypeId));
    }

    public function testExecuteCommandInvalidArgumentsThrowsError()
    {
        $subject = new Dig();
        $this->assertSame([], $subject->getDnsRawResult('ls', DNS_TXT));
    }

    public function testGetDnsRawResultInvalidGetCommandReturnsEmptyArray()
    {
        $subject = $this->getMockBuilder(Dig::class)
            ->onlyMethods(['getCommand'])
            ->getMock();
        $subject->method('getCommand')->willReturn('ls');
        $this->assertSame([], $subject->getDnsRawResult('test.com', DNS_TXT));
    }

    public function testGetCommandNoRecordName()
    {
        $this->assertSame([], $this->subject->getDnsRawResult('test.com', 99999999999999));
    }

    public function testCanExecuteDig()
    {
        $subject = new Dig();
        $this->assertIsBool($subject->canUseDig());
    }

    public function validCommandsDataProvider(): array
    {
        return [
            ['', false],
            ['ls', false],
            ['dir', false],
            ['dig | ls', false],
            ['wget', false],
            ['wget', false],
            ['dig +nocmd +noall +authority +answer +nomultiline +tries=1 +time=5 test.com ABCDEF', false],
            ['dig +nocmd +noall +authority +answer +nomultiline +tries=1 +time=5 test.com A ', false],
            ['dig +nocmd +noall +authority +answer +nomultiline +tries=1 +time=5 test.com A @8.8', false],
            ['dig +nocmd +noall +authority +answer +nomultiline +tries=1 +time=5 test.com A @8.8.8.8 ', false],
            ['dig +nocmd +noall +authority +answer +nomultiline +tries=1 +time=5 test.com A @192.168.0.1 ', false],
            ['dig +nocmd +noall +authority +answer +nomultiline +tries=1 +time=5 test.com A @192.1168.0.1', false],

            ['dig +nocmd +noall +authority +answer +nomultiline +tries=1 +time=5 test.com ABCDE', true],
            ['dig +nocmd +noall +authority +answer +nomultiline +tries=1 +time=5 test.com A', true],
            ['dig +nocmd +noall +authority +answer +nomultiline +tries=1 +time=5 test.com A @8.8.8.8', true],
            ['dig +nocmd +noall +authority +answer +nomultiline +tries=1 +time=5 test.com A @192.168.0.1', true],
        ];
    }

    /**
     * @return void
     * @dataProvider validCommandsDataProvider
     */
    public function testIsValidCommand($command, $expected)
    {
        $this->assertSame($expected, $this->subject->isValidCommand($command));
    }

    public function testSetNameserverInvalidThrowsException()
    {
        $this->expectException(DnsHandlerException::class);
        $this->expectExceptionMessage('Unable to set nameserver, as "test" is an invalid IPV4 format!');
        $this->expectExceptionCode(DnsHandlerException::INVALID_NAMESERVER);
        $this->subject->setNameserver('test');
    }

    /**
     * @throws DnsHandlerException
     */
    public function testSetNameserverValidReturnsSelf()
    {
        $this->assertSame($this->subject, $this->subject->setNameserver('8.8.8.8'));
    }

}
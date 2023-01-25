<?php

namespace BlueLibraries\Dns\Test\Unit\Records\Types\Txt;

use BlueLibraries\Dns\Records\ExtendedTxtRecords;
use BlueLibraries\Dns\Records\Types\Txt\SPF;
use BlueLibraries\Dns\Test\Unit\Records\RecordTestClass;

/**
 * @property SPF $subject
 */
class SPFTest extends RecordTestClass
{
    public function setUp(): void
    {
        $this->subject = new SPF([]);
        parent::setUp();
    }

    public function testGetTxt()
    {
        $this->assertNull($this->subject->getTxt());
    }

    public function testGetIpValue()
    {
        $value = 'random text here';
        $this->subject->setData(['txt' => $value]);
        $this->assertSame($value, $this->subject->getTxt());
    }

    public function testToStringDefault()
    {
        $this->assertSame('0 IN TXT', $this->subject->toString());
    }

    public function testToStringComplete()
    {
        $this->subject->setData(
            [
                'ttl'  => 7200,
                'host' => 'test.com',
                'txt'  => 'text here'
            ]
        );
        $this->assertSame('test.com 7200 IN TXT "text here"', $this->subject->toString());
    }

    public function testToStringCompleteWithChaosClass()
    {
        $this->subject->setData(
            [
                'ttl'   => 7200,
                'class' => 'CH',
                'host'  => 'test.com',
                'txt'   => 'text here'
            ]
        );
        $this->assertSame('test.com 7200 CH TXT "text here"', $this->subject->toString());
    }

    public function testGetEmptyText()
    {
        $this->subject->setData(
            [
                'ttl'   => 7200,
                'class' => 'IN',
                'host'  => 'test.com',
                'txt'   => ''
            ]
        );
        $this->assertSame('test.com 7200 IN TXT ""', $this->subject->toString());
    }

    public function testGetEmptyHosts()
    {
        $this->subject->setData(
            [
                'ttl'   => 7200,
                'class' => 'IN',
                'host'  => 'test.com',
                'txt'   => ''
            ]
        );
        $this->assertSame([], $this->subject->getHosts());
    }

    public function testGetInvalidHosts()
    {
        $this->subject->setData(
            [
                'ttl'   => 7200,
                'class' => 'IN',
                'host'  => 'test.com',
                'txt'   => 'balanel si mienuel'
            ]
        );
        $this->assertSame([], $this->subject->getHosts());
    }

    public function testValidHosts()
    {
        $this->subject->setData(
            [
                'ttl'   => 7200,
                'class' => 'IN',
                'host'  => 'test.com',
                'txt'   => 'v=spf1 sarmale.ro coliva.ro'
            ]
        );
        $this->assertSame(['sarmale.ro', 'coliva.ro'], $this->subject->getHosts());
    }

    public function testGetExtendedTypeName()
    {
        $this->assertSame(ExtendedTxtRecords::SPF, $this->subject->getTypeName());
    }

}

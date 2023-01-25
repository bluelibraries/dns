<?php

namespace BlueLibraries\Dns\Test\Unit\Records\Types;

use BlueLibraries\Dns\Records\Types\Txt;
use BlueLibraries\Dns\Test\Unit\Records\RecordTestClass;

/**
 * @property TXT $subject
 */
class TXTTest extends RecordTestClass
{
    public function setUp(): void
    {
        $this->subject = new TXT([]);
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

    public function testEntriesAreRemoved()
    {
        $this->subject->setData(['entries' => 'test']);
        $this->assertSame([
            'host'  => '',
            'ttl'   => 0,
            'class' => 'IN',
            'type'  => 'TXT',
        ], $this->subject->toArray()
        );
    }

    public function testToStringDefault()
    {
        $this->assertSame('0 IN TXT', $this->subject->toString());
    }

    public function testToStringMagicMethodDefault()
    {
        $this->assertSame('0 IN TXT', (string)$this->subject);
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

    public function testToStringMagicMethod()
    {
        $this->subject->setData(
            [
                'ttl'  => 7200,
                'host' => 'test.com',
                'txt'  => 'text here'
            ]
        );
        $this->assertSame('test.com 7200 IN TXT "text here"', (string)$this->subject);
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

}

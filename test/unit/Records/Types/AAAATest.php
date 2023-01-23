<?php

namespace BlueLibraries\Dns\Test\Unit\Records\Types;

use BlueLibraries\Dns\Records\Types\AAAA;
use BlueLibraries\Dns\Test\Unit\Records\AbstractRecordTestClass;

/**
 * @property AAAA $subject
 */
class AAAATest extends AbstractRecordTestClass
{
    public function setUp(): void
    {
        $this->subject = new AAAA([]);
        parent::setUp();
    }

    public function testGetIp()
    {
        $this->assertNull($this->subject->getIPV6());
    }

    public function testGetIPV6Value()
    {
        $value = '::ffff:1451:6f55';
        $this->subject->setData(['ipv6' => $value]);
        $this->assertSame($value, $this->subject->getIPV6());
    }

    public function testToStringDefault()
    {
        $this->assertSame('0 IN AAAA', $this->subject->toString());
    }

    public function testToStringComplete()
    {
        $this->subject->setData(
            [
                'host' => 'test.com',
                'ipv6' => '::ffff:1451:6f55'
            ]
        );
        $this->assertSame('test.com 0 IN AAAA ::ffff:1451:6f55', $this->subject->toString());
    }

    public function testJson()
    {
        $this->assertSame(json_encode($this->subject->toArray()), json_encode($this->subject));
    }

}

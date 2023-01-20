<?php

namespace BlueLibraries\Dns\Test\Unit\Records\Types;

use BlueLibraries\Dns\Records\Types\MX;
use BlueLibraries\Dns\Test\Unit\Records\AbstractRecordTestClass;

/**
 * @property MX $subject
 */
class MXTest extends AbstractRecordTestClass
{
    public function setUp(): void
    {
        $this->subject = new MX([]);
        parent::setUp();
    }

    public function testGetTarget()
    {
        $this->assertNull($this->subject->getTarget());
    }

    public function testGetTargetValue()
    {
        $value = 'test.target.com';
        $this->subject->setData(['target' => $value]);
        $this->assertSame($value, $this->subject->getTarget());
    }

    public function testGetPriority()
    {
        $this->assertNull($this->subject->getPriority());
    }

    public function testGetPriorityValue()
    {
        $value = '10';
        $this->subject->setData(['pri' => $value]);
        $this->assertSame((int)$value, $this->subject->getPriority());
    }

    public function testToStringDefault()
    {
        $this->assertSame('0 IN MX', $this->subject->toString());
    }

    public function testToStringComplete()
    {
        $this->subject->setData(
            [
                'host'   => 'test.com',
                'pri'    => 10,
                'target' => '192.168.0.1'
            ]
        );
        $this->assertSame('test.com 0 IN MX 10 192.168.0.1', $this->subject->toString());
    }

}
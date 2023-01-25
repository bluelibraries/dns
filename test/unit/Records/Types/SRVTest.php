<?php

namespace BlueLibraries\Dns\Test\Unit\Records\Types;

use BlueLibraries\Dns\Records\Types\SRV;
use BlueLibraries\Dns\Test\Unit\Records\RecordTestClass;

/**
 * @property SRV $subject
 */
class SRVTest extends RecordTestClass
{
    public function setUp(): void
    {
        $this->subject = new SRV([]);
        parent::setUp();
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

    public function testGetWeight()
    {
        $this->assertNull($this->subject->getWeight());
    }

    public function testGetWeightValue()
    {
        $value = '10';
        $this->subject->setData(['weight' => $value]);
        $this->assertSame((int)$value, $this->subject->getWeight());
    }

    public function testGetPort()
    {
        $this->assertNull($this->subject->getPort());
    }

    public function testGetPortValue()
    {
        $value = '64';
        $this->subject->setData(['port' => $value]);
        $this->assertSame((int)$value, $this->subject->getPort());
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

    public function testToStringDefault()
    {
        $this->assertSame('0 IN SRV', $this->subject->toString());
    }

    public function testToStringComplete()
    {
        $this->subject->setData(
            [
                'host'   => 'srv.test.com',
                'pri'    => 1,
                'port'   => 10,
                'target' => '192.168.0.1',
                'weight' => 9,
            ]
        );
        $this->assertSame('srv.test.com 0 IN SRV 1 9 10 192.168.0.1', $this->subject->toString());
    }

}

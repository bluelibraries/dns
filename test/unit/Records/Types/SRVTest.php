<?php

namespace MamaOmida\Dns\Test\Unit\Records\Types;

use MamaOmida\Dns\Records\Types\SRV;
use MamaOmida\Dns\Test\Unit\Records\AbstractRecordTestClass;

/**
 * @property SRV $subject
 */
class SRVTest extends AbstractRecordTestClass
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





}
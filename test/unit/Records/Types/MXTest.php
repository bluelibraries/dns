<?php

namespace MamaOmidaTest\Dns\Unit\Records\Types;

use MamaOmida\Dns\Records\Types\MX;
use MamaOmidaTest\Dns\Unit\Records\AbstractRecordTestClass;

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



}
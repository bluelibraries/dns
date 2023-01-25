<?php

namespace BlueLibraries\Dns\Test\Unit\Records\Types;

use BlueLibraries\Dns\Records\Types\PTR;
use BlueLibraries\Dns\Test\Unit\Records\RecordTestClass;

/**
 * @property PTR $subject
 */
class PTRTest extends RecordTestClass
{
    public function setUp(): void
    {
        $this->subject = new PTR([]);
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

    public function testToStringDefault()
    {
        $this->assertSame('0 IN PTR', $this->subject->toString());
    }

    public function testToStringComplete()
    {
        $this->subject->setData(
            [
                'host'   => 'test.com',
                'target' => '192.168.0.1'
            ]
        );
        $this->assertSame('test.com 0 IN PTR 192.168.0.1', $this->subject->toString());
    }

}

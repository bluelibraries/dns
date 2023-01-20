<?php

namespace Unit\Records\Types;

use BlueLibraries\Dns\Records\Types\HInfo;
use BlueLibraries\Dns\Test\Unit\Records\AbstractRecordTestClass;

/**
 * @property HInfo $subject
 */
class HInfoTest extends AbstractRecordTestClass
{
    public function setUp(): void
    {
        $this->subject = new HInfo([]);
        parent::setUp();
    }

    public function testGetHardware()
    {
        $this->assertNull($this->subject->getHardware());
    }

    public function testGetHardwareValue()
    {
        $value = 'AMD K6 166 MHz';
        $this->subject->setData(['hardware' => $value]);
        $this->assertSame($value, $this->subject->getHardware());
    }

    public function testGetOperatingSystem()
    {
        $this->assertNull($this->subject->getOperatingSystem());
    }

    public function testGetOperatingSystemValue()
    {
        $value = 'Win 3.1';
        $this->subject->setData(['os' => $value]);
        $this->assertSame($value, $this->subject->getOperatingSystem());
    }

    public function testToStringDefault()
    {
        $this->assertSame('0 IN HINFO', $this->subject->toString());
    }

    public function testToStringComplete()
    {
        $this->subject->setData(
            [
                'host'   => 'test.com',
                'hardware' => 'Pentium 1',
                'os' => 'Win 95',
            ]
        );
        $this->assertSame('test.com 0 IN HINFO "Pentium 1" "Win 95"', $this->subject->toString());
    }

}

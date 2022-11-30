<?php

namespace MamaOmida\Dns\Test\Unit\Records\Types;

use MamaOmida\Dns\Records\Types\CAA;
use MamaOmida\Dns\Test\Unit\Records\AbstractRecordTestClass;

/**
 * @property CAA $subject
 */
class CAATest extends AbstractRecordTestClass
{
    public function setUp(): void
    {
        $this->subject = new CAA([]);
        parent::setUp();
    }

    public function testGetValue()
    {
        $this->assertNull($this->subject->getValue());
    }

    public function testGetValueSetValue()
    {
        $value = 'caa value';
        $this->subject->setData(['value' => $value]);
        $this->assertSame($value, $this->subject->getValue());
    }

    public function testGetFlags()
    {
        $this->assertNull($this->subject->getFlags());
    }

    public function testGetValueSetFlags()
    {
        $value = 'caa value';
        $this->subject->setData(['flags' => $value]);
        $this->assertSame($value, $this->subject->getFlags());
    }

    public function testGetTag()
    {
        $this->assertNull($this->subject->getTag());
    }

    public function testGetTagSetValue()
    {
        $value = 'caa tag';
        $this->subject->setData(['tag' => $value]);
        $this->assertSame($value, $this->subject->getTag());
    }

    public function testToStringDefault()
    {
        $this->assertSame('0 IN CAA', $this->subject->toString());
    }

    public function testToStringComplete()
    {
        $this->subject->setData(
            [
                'host'  => 'test.com',
                'value' => 'value',
                'flags' => 'flags',
                'tag'   => 'tag'
            ]
        );
        $this->assertSame('test.com 0 IN CAA flags tag value', $this->subject->toString());
    }

}
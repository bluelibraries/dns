<?php

namespace BlueLibraries\Dns\Test\Unit\Records\Types;

use BlueLibraries\Dns\Records\Types\CAA;
use BlueLibraries\Dns\Test\Unit\Records\AbstractRecordTestClass;

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

    public function testGetFlag()
    {
        $this->assertNull($this->subject->getFlags());
    }

    public function testGetValueSetFlag()
    {
        $value = 1;
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
                'flags'  => 1,
                'tag'   => 'tag'
            ]
        );
        $this->assertSame('test.com 0 IN CAA 1 tag value', $this->subject->toString());
    }

}

<?php

namespace Unit\Records\Types;

use MamaOmida\Dns\Records\Types\HTTPS;
use MamaOmida\Dns\Test\Unit\Records\AbstractRecordTestClass;

/**
 * @property HTTPS $subject
 */
class HTTPSTest extends AbstractRecordTestClass
{
    public function setUp(): void
    {
        $this->subject = new HTTPS([]);
        parent::setUp();
    }

    public function testGetSeparator()
    {
        $this->assertNull($this->subject->getSeparator());
    }

    public function testGetSeparatorValue()
    {
        $value = '\#';
        $this->subject->setData(['separator' => $value]);
        $this->assertSame($value, $this->subject->getSeparator());
    }

    public function testGetOriginalLength()
    {
        $this->assertNull($this->subject->getOriginalLength());
    }

    public function testGetOriginalLengthValue()
    {
        $value = 34;
        $this->subject->setData(['original-length' => $value]);
        $this->assertSame($value, $this->subject->getOriginalLength());
    }

    public function testGetData()
    {
        $this->assertNull($this->subject->getData());
    }

    public function testGetDataValue()
    {
        $value = '1000C0268330568332D3239';
        $this->subject->setData(['data' => $value]);
        $this->assertSame($value, $this->subject->getData());
    }

    public function testToStringDefault()
    {
        $this->assertSame('0 IN TYPE65', $this->subject->toString());
    }

    public function testToStringComplete()
    {
        $this->subject->setData(
            [
                'host'            => 'test.com',
                'ttl'             => 3600,
                'separator'       => '\#',
                'original-length' => 27,
                'data'            => '1000C0268330568332D3239AA'
            ]
        );
        $this->assertSame('test.com 3600 IN TYPE65 \# 27 1000C0268330568332D3239AA', $this->subject->toString());
    }

}

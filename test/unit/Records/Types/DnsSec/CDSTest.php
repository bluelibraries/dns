<?php

namespace Unit\Records\Types\DnsSec;

use BlueLibraries\Dns\Records\Types\DnsSec\CDS;
use BlueLibraries\Dns\Test\Unit\Records\AbstractRecordTestClass;

/**
 * @property CDS $subject
 */
class CDSTest extends AbstractRecordTestClass
{
    public function setUp(): void
    {
        $this->subject = new CDS([]);
        parent::setUp();
    }

    public function testGetKeyTag()
    {
        $this->assertNull($this->subject->getKeyTag());
    }

    public function testGetValueSetKeyTag()
    {
        $value = 2371;
        $this->subject->setData(['key-tag' => $value]);
        $this->assertSame($value, $this->subject->getKeyTag());
    }

    public function testGetAlgorithm()
    {
        $this->assertNull($this->subject->getAlgorithm());
    }

    public function testGetValueSetAlgorithm()
    {
        $value = 13;
        $this->subject->setData(['algorithm' => $value]);
        $this->assertSame($value, $this->subject->getAlgorithm());
    }

    public function testGetAlgorithmDigest()
    {
        $this->assertNull($this->subject->getAlgorithmDigest());
    }

    public function testGetValueSettAlgorithmDigest()
    {
        $value = 2;
        $this->subject->setData(['algorithm-digest' => $value]);
        $this->assertSame($value, $this->subject->getAlgorithmDigest());
    }

    public function testGetDigest()
    {
        $this->assertNull($this->subject->getDigest());
    }

    public function testGetValueSetDigest()
    {
        $value = '1F987CC6583E92DF0890718C42';
        $this->subject->setData(['digest' => $value]);
        $this->assertSame($value, $this->subject->getDigest());
    }

    public function testToStringDefault()
    {
        $this->assertSame('0 IN CDS', $this->subject->toString());
    }

    public function testToStringComplete()
    {
        $this->subject->setData(
            [
                'host'             => 'test.com',
                'ttl'              => '3600',
                'key-tag'          => 2371,
                'algorithm'        => 13,
                'algorithm-digest' => 3,
                'digest'           => '1F987CC6583E92DF0890718C42'
            ]
        );
        $this->assertSame('test.com 3600 IN CDS 2371 13 3 1F987CC6583E92DF0890718C42', $this->subject->toString());
    }

}

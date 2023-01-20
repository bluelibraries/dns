<?php

namespace Unit\Records\Types\DnsSec;

use BlueLibraries\Dns\Records\Types\DnsSec\NSEC3Param;
use BlueLibraries\Dns\Test\Unit\Records\AbstractRecordTestClass;

/**
 * @property NSEC3Param $subject
 */
class NSEC3ParamTest extends AbstractRecordTestClass
{
    public function setUp(): void
    {
        $this->subject = new NSEC3Param([]);
        parent::setUp();
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

    public function testGetFlags()
    {
        $this->assertNull($this->subject->getFlags());
    }

    public function testGetValueSetFlags()
    {
        $value = 257;
        $this->subject->setData(['flags' => $value]);
        $this->assertSame($value, $this->subject->getFlags());
    }

    public function testGetIterations()
    {
        $this->assertNull($this->subject->getIterations());
    }

    public function testGetValueSetIterations()
    {
        $value = 3;
        $this->subject->setData(['iterations' => $value]);
        $this->assertSame($value, $this->subject->getIterations());
    }

    public function testGetSalt()
    {
        $this->assertNull($this->subject->getSalt());
    }

    public function testGetValueSetSalt()
    {
        $value = 'LofZcndFN2aVsd==';
        $this->subject->setData(['salt' => $value]);
        $this->assertSame($value, $this->subject->getSalt());
    }

    public function testToStringDefault()
    {
        $this->assertSame('0 IN NSEC3PARAM', $this->subject->toString());
    }

    public function testToStringComplete()
    {
        $this->subject->setData(
            [
                'host'       => 'test.com',
                'ttl'        => '3600',
                'value'      => 'value',
                'algorithm'  => 12,
                'flags'      => 255,
                'iterations' => 3,
                'salt'       => 'salt==',
            ]
        );
        $this->assertSame('test.com 3600 IN NSEC3PARAM 12 255 3 salt==', $this->subject->toString());
    }

}

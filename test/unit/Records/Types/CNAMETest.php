<?php

namespace MamaOmida\Dns\Test\Unit\Records\Types;

use MamaOmida\Dns\Records\Types\CNAME;
use MamaOmida\Dns\Test\Unit\Records\AbstractRecordTestClass;

/**
 * @property CNAME $subject
 */
class CNAMETest extends AbstractRecordTestClass
{
    public function setUp(): void
    {
        $this->subject = new CNAME([]);
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
        $this->assertSame('0 IN CNAME', $this->subject->toString());
    }

    public function testToStringComplete()
    {
        $this->subject->setData(
            [
                'host' => 'test.com',
                'target' => 'target.test.com'
            ]
        );
        $this->assertSame('test.com 0 IN CNAME target.test.com', $this->subject->toString());
    }

}
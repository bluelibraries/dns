<?php

namespace MamaOmidaTest\Dns\Unit\Records\Types;

use MamaOmida\Dns\Records\Types\CNAME;
use MamaOmidaTest\Dns\Unit\Records\AbstractRecordTestClass;

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

}
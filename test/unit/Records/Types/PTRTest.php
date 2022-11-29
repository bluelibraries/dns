<?php

namespace MamaOmida\Dns\Test\Unit\Records\Types;

use MamaOmida\Dns\Records\Types\PTR;
use MamaOmida\Dns\Test\Unit\Records\AbstractRecordTestClass;

/**
 * @property PTR $subject
 */
class PTRTest extends AbstractRecordTestClass
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

}
<?php

namespace MamaOmidaTest\Dns\Unit\Records\Types;

use MamaOmida\Dns\Records\Types\TXT;
use MamaOmidaTest\Dns\Unit\Records\AbstractRecordTestClass;

/**
 * @property TXT $subject
 */
class TXTTest extends AbstractRecordTestClass
{
    public function setUp(): void
    {
        $this->subject = new TXT([]);
        parent::setUp();
    }

    public function testGetTxt()
    {
        $this->assertNull($this->subject->getTxt());
    }

    public function testGetIpValue()
    {
        $value = 'random text here';
        $this->subject->setData(['txt' => $value]);
        $this->assertSame($value, $this->subject->getTxt());
    }

}
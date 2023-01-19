<?php

namespace Unit\Handlers\Types;

use MamaOmida\Dns\Handlers\Types\UDP;
use PHPUnit\Framework\TestCase;

class UDPTest extends TestCase
{
    /**
     * @var UDP
     */
    protected $subject;

    public function setUp(): void
    {
        parent::setUp();
        $this->subject = new UDP();
    }

    public function testSetPort()
    {
        $this->assertSame($this->subject, $this->subject->setPort(54));
    }

}

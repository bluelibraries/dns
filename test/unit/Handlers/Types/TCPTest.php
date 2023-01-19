<?php

namespace MamaOmida\Dns\Test\Unit\Handlers\Types;

use MamaOmida\Dns\Handlers\Types\TCP;
use PHPUnit\Framework\TestCase;

class TCPTest extends TestCase
{
    /**
     * @var TCP
     */
    protected $subject;

    public function setUp(): void
    {
        parent::setUp();
        $this->subject = new TCP();
    }

    public function testSetPort()
    {
        $this->assertSame($this->subject, $this->subject->setPort(54));
    }

}

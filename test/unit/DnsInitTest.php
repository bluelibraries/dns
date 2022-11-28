<?php

namespace MamaOmida\Dns\Test\Unit;

use MamaOmida\Dns\Dns;
use MamaOmida\Dns\Handlers\Types\DnsGetRecord;
use MamaOmida\Dns\Records\RecordFactory;
use PHPUnit\Framework\TestCase;

class DnsInitTest extends TestCase
{

    public function testInjectNullHandler()
    {
        $subject = new Dns(null, new RecordFactory());
        $this->assertEquals($subject->getHandler(), new DnsGetRecord());
    }

    public function testInjectNullFactory()
    {
        $subject = new Dns(new DnsGetRecord(), null);
        $this->assertEquals($subject->getFactory(), new RecordFactory());
    }

    public function testInjectNullDependencies()
    {
        $subject = new Dns(null, null);
        $this->assertEquals($subject->getHandler(), new DnsGetRecord());
        $this->assertEquals($subject->getFactory(), new RecordFactory());
    }

    public function testReturnSameHandler()
    {
        $handler = new DnsGetRecord();
        $subject = new Dns($handler, null);
        $this->assertSame($handler, $subject->getHandler());
    }

    public function testReturnSameFactory()
    {
        $factory = new RecordFactory();
        $subject = new Dns(null, $factory);
        $this->assertSame($factory, $subject->getFactory());
    }

    public function testSetHandler()
    {
        $handler = new DnsGetRecord();
        $subject = new Dns(null, null);
        $subject->setHandler($handler);
        $this->assertSame($handler, $subject->getHandler());
    }

    public function testSetFactory()
    {
        $factory = new RecordFactory();
        $subject = new Dns(null, null);
        $subject->setFactory($factory);
        $this->assertSame($factory, $subject->getFactory());
    }


}
<?php

namespace MamaOmida\Dns\Test\Unit;

use MamaOmida\Dns\DnsRecords;
use MamaOmida\Dns\Handlers\Types\DnsGetRecord;
use MamaOmida\Dns\Records\RecordFactory;
use PHPUnit\Framework\TestCase;

class DnsInitTest extends TestCase
{

    public function testInjectNullHandler()
    {
        $subject = new DnsRecords(null, new RecordFactory());
        $this->assertEquals($subject->getHandler(), new DnsGetRecord());
    }

    public function testInjectNullFactory()
    {
        $subject = new DnsRecords(new DnsGetRecord(), null);
        $this->assertEquals($subject->getFactory(), new RecordFactory());
    }

    public function testInjectNullDependencies()
    {
        $subject = new DnsRecords(null, null);
        $this->assertEquals($subject->getHandler(), new DnsGetRecord());
        $this->assertEquals($subject->getFactory(), new RecordFactory());
    }

    public function testReturnSameHandler()
    {
        $handler = new DnsGetRecord();
        $subject = new DnsRecords($handler, null);
        $this->assertSame($handler, $subject->getHandler());
    }

    public function testReturnSameFactory()
    {
        $factory = new RecordFactory();
        $subject = new DnsRecords(null, $factory);
        $this->assertSame($factory, $subject->getFactory());
    }

    public function testSetHandler()
    {
        $handler = new DnsGetRecord();
        $subject = new DnsRecords(null, null);
        $subject->setHandler($handler);
        $this->assertSame($handler, $subject->getHandler());
    }

    public function testSetFactory()
    {
        $factory = new RecordFactory();
        $subject = new DnsRecords(null, null);
        $subject->setFactory($factory);
        $this->assertSame($factory, $subject->getFactory());
    }

}

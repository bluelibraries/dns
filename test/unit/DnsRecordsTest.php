<?php

namespace BlueLibraries\Dns\Test\Unit;

use BlueLibraries\Dns\DnsRecords;
use BlueLibraries\Dns\Handlers\DnsHandlerException;
use BlueLibraries\Dns\Handlers\DnsHandlerInterface;
use BlueLibraries\Dns\Records\RecordTypes;
use BlueLibraries\Dns\Records\RecordException;
use BlueLibraries\Dns\Records\RecordFactory;
use BlueLibraries\Dns\Records\RecordInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class DnsRecordsTest extends TestCase
{
    private DnsRecords $subject;

    /**
     * @var DnsHandlerInterface|MockObject
     */
    private $handler;

    /**
     * @var RecordFactory|MockObject
     */
    private $factory;


    public function setUp(): void
    {
        parent::setUp();

        $this->handler = $this->getMockBuilder(DnsHandlerInterface::class)
            ->getMock();
        $this->factory = $this->getMockBuilder(RecordFactory::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->subject = new DnsRecords($this->handler, $this->factory);
    }

    /**
     * @throws RecordException
     * @throws DnsHandlerException
     */
    public function testGetRecordsEmptyArray()
    {
        $this->handler->method('getDnsData')->willReturn([]);
        $this->factory->expects($this->never())->method('create');
        $this->assertSame([], $this->subject->get('test.test', RecordTypes::A));
    }

    public function allRecordTypesFormattedClassesDataProvider(): array
    {
        return require "Data/allRecordsTypesFormattedClasses.php";
    }

//    /**
//     * @return void
//     * @throws DnsHandlerException
//     * @throws RecordException
//     * @dataProvider allRecordTypesFormattedClassesDataProvider
//     */
//    public function testGetRecordsReturnTypeA(array $data, string $className)
//    {
//
//        $this->handler->method('getDnsData')->willReturn([$data]);
//
//        /** @var RecordInterface $recordTypeA */
//        $recordTypeA = new $className($data);
//
//        $this->factory->method('create')
//            ->willReturn($recordTypeA);
//
//        $records = $this->subject->get($data['host'], RecordTypes::A);
//
//        $this->assertSame($recordTypeA, $records[3]);
//    }

}

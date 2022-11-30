<?php

namespace MamaOmida\Dns\Test\Unit;

use MamaOmida\Dns\Dns;
use MamaOmida\Dns\Handlers\DnsHandlerException;
use MamaOmida\Dns\Handlers\DnsHandlerInterface;
use MamaOmida\Dns\Records\RecordException;
use MamaOmida\Dns\Records\RecordFactory;
use MamaOmida\Dns\Records\RecordInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class DnsTest extends TestCase
{
    private DNS $subject;

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

        $this->subject = new Dns($this->handler, $this->factory);
    }

    public function testGetRecordsEmptyArray()
    {
        $this->handler->method('getDnsData')->willReturn([]);
        $this->factory->expects($this->never())->method('create');
        $this->assertSame([], $this->subject->getRecords('test.test'));
    }

    public function allRecordTypesFormattedClassesDataProvider(): array
    {
        return require "Data/allRecordsTypesFormattedClasses.php";
    }

    /**
     * @return void
     * @throws DnsHandlerException
     * @throws RecordException
     * @dataProvider allRecordTypesFormattedClassesDataProvider
     */
    public function testGetRecordsReturnTypeA(array $data, string $className)
    {

        $this->handler->method('getDnsData')->willReturn([$data]);

        /** @var RecordInterface $recordTypeA */
        $recordTypeA = new $className($data);

        $this->factory->method('create')
            ->willReturn($recordTypeA);

        $records = $this->subject->getRecords($data['host']);

        $this->assertSame($recordTypeA, $records[0]);
    }

}
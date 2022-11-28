<?php

namespace MamaOmida\DNS\Test\Unit\Records;

use MamaOmida\Dns\Records\RecordException;
use MamaOmida\Dns\Records\RecordFactory;
use PHPUnit\Framework\TestCase;

class RecordFactoryTest extends TestCase
{

    protected RecordFactory $subject;

    public function setUp(): void
    {
        parent::setUp();
        $this->subject = new RecordFactory();
    }

    public function allRecordTypesFormattedClassesDataProvider(): array
    {
        return require dirname(__FILE__) . "/../Data/allRecordsTypesFormattedClasses.php";
    }

    /**
     * @return void
     * @dataProvider allRecordTypesFormattedClassesDataProvider
     * @throws RecordException
     */
    public function testCreate(array $data, string $class)
    {
        $record = $this->subject->create($data);
        $this->assertSame(get_class($record), $class);
        $this->assertSame($data, $record->toArray());
    }

    public function testCreateMissingRecordTypeThrowsException()
    {
        $this->expectException(RecordException::class);
        $this->expectExceptionCode(RecordException::UNABLE_TO_CREATE_RECORD);
        $this->expectExceptionMessage('Invalid record type for recordData: []');
        $this->subject->create([]);
    }

    public function testCreateInvalidRecordTypeThrowsException()
    {
        $this->expectException(RecordException::class);
        $this->expectExceptionCode(RecordException::UNABLE_TO_CREATE_RECORD);
        $this->expectExceptionMessage('Invalid record type for recordData: {"type":"INVALID"}');
        $this->subject->create(['type' => 'INVALID']);
    }

    public function testCreateTypeAnyThrowsException()
    {
        $this->expectException(RecordException::class);
        $this->expectExceptionCode(RecordException::UNABLE_TO_CREATE_RECORD_TYPE);
        $this->expectExceptionMessage('Unable to create record type 268435456');
        $this->subject->create(['type' => 'ANY']);
    }

    public function testCreateTypeAllThrowsException()
    {
        $this->expectException(RecordException::class);
        $this->expectExceptionCode(RecordException::UNABLE_TO_CREATE_RECORD_TYPE);
        $this->expectExceptionMessage('Unable to create record type 251721779');
        $this->subject->create(['type' => 'ALL']);
    }


}
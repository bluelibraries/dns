<?php

namespace BlueLibraries\Dns\Test\Unit\Records;

use BlueLibraries\Dns\Records\RecordException;
use BlueLibraries\Dns\Records\RecordFactory;
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
    public function testCreateDefaultRecords(array $data, string $class, string $classExtended)
    {
        $record = $this->subject->create($data, false);
        $this->assertSame(get_class($record), $class);
        $this->assertSame($data, $record->toArray());
    }

    /**
     * @return void
     * @dataProvider allRecordTypesFormattedClassesDataProvider
     * @throws RecordException
     */
    public function testCreateExtendedRecords(array $data, string $class, string $classExtended)
    {
        $record = $this->subject->create($data, true);
        $this->assertSame(get_class($record), $classExtended);
        $this->assertSame($data, $record->toArray());
    }

    public function testCreateMissingRecordTypeThrowsException()
    {
        $this->expectException(RecordException::class);
        $this->expectExceptionCode(RecordException::UNABLE_TO_CREATE_RECORD);
        $this->expectExceptionMessage('Invalid record type for recordData: []');
        $this->subject->create([], false);
    }

    public function testCreateInvalidRecordTypeThrowsException()
    {
        $this->expectException(RecordException::class);
        $this->expectExceptionCode(RecordException::UNABLE_TO_CREATE_RECORD);
        $this->expectExceptionMessage('Invalid record type for recordData: {"type":"INVALID"}');
        $this->subject->create(['type' => 'INVALID'], false);
    }

}
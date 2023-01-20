<?php

namespace Unit\Handlers\Raw;

use BlueLibraries\Dns\Handlers\Raw\RawClassTypes;
use PHPUnit\Framework\TestCase;

class RawClassTypesTest extends TestCase
{

    public function rawClassesDataProvider(): array
    {
        return [
            [1, 'IN'],
            [2, 'CS'],
            [3, 'CH'],
            [4, 'HS'],
            [99, null],
        ];
    }

    /**
     * @param int $classId
     * @param string|null $expected
     * @dataProvider rawClassesDataProvider
     * @return void
     */
    public static function testGetClassNameByRawType(int $classId, ?string $expected)
    {
        static::assertSame($expected, RawClassTypes::getClassNameByRawType($classId));
    }

}

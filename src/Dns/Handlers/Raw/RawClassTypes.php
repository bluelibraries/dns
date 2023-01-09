<?php

namespace MamaOmida\Dns\Handlers\Raw;

class RawClassTypes
{
    private static array $rawClassTypes = [
        'IN' => 1, // Internet
        'CS' => 2, // CSNet -> obsolete
        'CH' => 3, // Chaos
        'HS' => 4, // Hesiod
    ];

    public static function getRawTypes(): array
    {
        return self::$rawClassTypes;
    }

    public static function getClassTypeId($className): int
    {
        return self::$rawClassTypes[$className] ?? 1;
    }

    public static function getClassNameByRawType($rawClassId): ?string
    {
        foreach (self::$rawClassTypes as $key => $type) {
            if ($rawClassId === $type) {
                return $key;
            }
        }
        return null;
    }

}

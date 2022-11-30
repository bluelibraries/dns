<?php

namespace MamaOmida\Dns\Records;

class DnsRecordProperties
{
    protected static array $defaultProperties = ['host', 'ttl', 'class', 'type'];

    protected static array $properties = [
        DnsRecordTypes::A     => ['ip'],
        DnsRecordTypes::AAAA  => ['ipv6'],
        DnsRecordTypes::CAA   => ['flags', 'tag', 'value'],
        DnsRecordTypes::CNAME => ['target'],
        DnsRecordTypes::SOA   => ['mname', 'rname', 'serial', 'refresh', 'retry', 'expire', 'minimum-ttl'],
        DnsRecordTypes::TXT   => ['txt'],
        DnsRecordTypes::NS    => ['target'],
        DnsRecordTypes::MX    => ['pri', 'target'],
        DnsRecordTypes::PTR   => ['target'],
        DnsRecordTypes::SRV   => ['pri', 'weight', 'port', 'target'],
    ];

    protected static array $numberProperties = [
        'ttl',
        'minimum-ttl',
        'expire',
        'retry',
        'refresh',
        'port',
        'pri',
        'weight'
    ];

    public static function getProperties(int $typeId): ?array
    {
        return self::$properties[$typeId] ?? null;
    }

    public static function getDefaultProperties(): array
    {
        return self::$defaultProperties;
    }

    public static function isNumberProperty(string $property): bool
    {
        return in_array($property, self::$numberProperties);
    }

    public static function getRecordTypeProperties(int $typeId): array
    {
        return array_merge(self::$defaultProperties, static::getProperties($typeId) ?? []);
    }

    /**
     * @param int $typeId
     * @param array $data
     * @return array
     */
    public static function getFilteredProperties(int $typeId, array $data): array
    {
        return array_filter(
            self::getMappedProperties($data, $typeId),
            [DnsRecordProperties::class, 'filterExceptNumbers']
        );
    }

    protected static function filterExceptNumbers($value): bool
    {
        return ($value !== null && $value !== false && $value !== '');
    }

    /**
     * @param array $data
     * @param int $typeId
     * @return array|string[]
     */
    private static function getMappedProperties(array $data, int $typeId): array
    {
        return array_map(
            function ($property) use ($data) {
                return $data[$property] ?? '';
            },
            DnsRecordProperties::getRecordTypeProperties($typeId)
        );
    }

}
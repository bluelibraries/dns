<?php

namespace MamaOmida\Dns\Records;

class DnsRecordTypes
{

    public const A = DNS_A;
    public const CNAME = DNS_CNAME;
    public const HINFO = DNS_HINFO;
    public const CAA = DNS_CAA;
    public const MX = DNS_MX;
    public const NS = DNS_NS;
    public const PTR = DNS_PTR;
    public const SOA = DNS_SOA;
    public const TXT = DNS_TXT;
    public const AAAA = DNS_AAAA;
    public const SRV = DNS_SRV;
    public const NAPTR = DNS_NAPTR;
    public const A6 = DNS_A6;
    public const ALL = DNS_ALL;
    public const ANY = DNS_ANY;

    private static array $types = [];

    private static array $names = [
        self::A     => 'A',
        self::CNAME => 'CNAME',
        self::HINFO => 'HINFO',
        self::CAA   => 'CAA',
        self::MX    => 'MX',
        self::NS    => 'NS',
        self::PTR   => 'PTR',
        self::SOA   => 'SOA',
        self::TXT   => 'TXT',
        self::AAAA  => 'AAAA',
        self::SRV   => 'SRV',
        self::NAPTR => 'NAPTR',
        self::A6    => 'A6',
        self::ALL   => 'ALL',
        self::ANY   => 'ANY'
    ];

    public static function getName(int $type): ?string
    {
        return static::$names[$type] ?? null;
    }

    public static function getType(string $name): ?int
    {
        if (empty(static::$types)) {
            static::$types = array_flip(static::$names);
        }
        return self::$types[$name] ?? null;
    }

}
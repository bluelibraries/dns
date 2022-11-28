<?php

namespace MamaOmida\Dns\Records\Types;

use MamaOmida\Dns\Records\AbstractRecord;
use MamaOmida\Dns\Records\DnsRecordTypes;

class SOA extends AbstractRecord
{

    public function getTypeId(): int
    {
        return DnsRecordTypes::SOA;
    }

    public function getMasterNameServer(): ?string
    {
        return $this->raw['mname'] ?? null;
    }

    public function getRawEmailName(): ?string
    {
        return $this->raw['rname'] ?? null;
    }

    public function getAdministratorEmailAddress(): ?string
    {
        if (empty($this->raw) || empty($this->raw['rname'])) {
            return null;
        }

        $parts = explode('.', $this->raw['rname']);
        $partsLength = count($parts);

        if ($partsLength < 3) {
            return null;
        }

        $result = '';

        foreach ($parts as $key => $part) {
            $separator = $key === 0 ?
                ''
                : ($key === ($partsLength - 2) ? '@' : '.');
            $result .= $separator . $part;
        }

        return $result;
    }


    public function getSerial(): ?int
    {
        return $this->raw['serial'] ?? null;
    }

    public function getRefresh(): ?int
    {
        return $this->raw['refresh'] ?? null;
    }

    public function getRetry(): ?int
    {
        return $this->raw['retry'] ?? null;
    }

    public function getExpire(): ?int
    {
        return $this->raw['expire'] ?? null;
    }

    public function getMinimumTtl(): ?int
    {
        return $this->raw['minimum-ttl'] ?? null;
    }

}
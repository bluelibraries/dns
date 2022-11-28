<?php

namespace MamaOmida\Dns\Records\Types;

use MamaOmida\Dns\Records\AbstractRecord;
use MamaOmida\Dns\Records\DnsRecordTypes;

class CAA extends AbstractRecord
{

    public function getTypeId(): int
    {
        return DnsRecordTypes::CAA;
    }

    public function getFlags(): ?string
    {
        return $this->raw['flags'] ?? null;
    }

    public function getWeight(): ?string
    {
        return $this->raw['tag'] ?? null;
    }

    public function getValue(): ?string
    {
        return $this->raw['value'] ?? null;
    }


}
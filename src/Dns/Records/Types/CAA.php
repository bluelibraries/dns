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
        return $this->data['flags'] ?? null;
    }

    public function getTag(): ?string
    {
        return $this->data['tag'] ?? null;
    }

    public function getValue(): ?string
    {
        return $this->data['value'] ?? null;
    }


}
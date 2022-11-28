<?php

namespace MamaOmida\Dns\Records\Types;

use MamaOmida\Dns\Records\AbstractRecord;
use MamaOmida\Dns\Records\DnsRecordTypes;

class SRV extends AbstractRecord
{

    public function getTypeId(): int
    {
        return DnsRecordTypes::SRV;
    }

    public function getPriority(): ?int
    {
        return $this->raw['pri'] ?? null;
    }

    public function getWeight(): ?int
    {
        return $this->raw['weight'] ?? null;
    }

    public function getPort(): ?int
    {
        return $this->raw['port'] ?? null;
    }

    public function getTarget(): ?string
    {
        return $this->raw['target'] ?? null;
    }


}
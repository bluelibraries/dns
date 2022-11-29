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
        return $this->data['pri'] ?? null;
    }

    public function getWeight(): ?int
    {
        return $this->data['weight'] ?? null;
    }

    public function getPort(): ?int
    {
        return $this->data['port'] ?? null;
    }

    public function getTarget(): ?string
    {
        return $this->data['target'] ?? null;
    }

}
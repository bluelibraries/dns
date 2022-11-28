<?php

namespace MamaOmida\Dns\Records\Types;

use MamaOmida\Dns\Records\AbstractRecord;
use MamaOmida\Dns\Records\DnsRecordTypes;

class MX extends AbstractRecord
{

    public function getTypeId(): int
    {
        return DnsRecordTypes::MX;
    }

    public function getTarget(): ?string
    {
        return $this->raw['target'] ?? null;
    }

    public function getPriority(): ?int
    {
        return $this->raw['pri'] ?? null;
    }


}
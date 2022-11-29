<?php

namespace MamaOmida\Dns\Records\Types;

use MamaOmida\Dns\Records\AbstractRecord;
use MamaOmida\Dns\Records\DnsRecordTypes;

class PTR extends AbstractRecord
{

    public function getTypeId(): int
    {
        return DnsRecordTypes::PTR;
    }

    public function getTarget(): ?string
    {
        return $this->data['target'] ?? null;
    }

}
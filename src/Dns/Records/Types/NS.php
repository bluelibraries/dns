<?php

namespace MamaOmida\Dns\Records\Types;

use MamaOmida\Dns\Records\AbstractRecord;
use MamaOmida\Dns\Records\DnsRecordTypes;

class NS extends AbstractRecord
{

    public function getTypeId(): int
    {
        return DnsRecordTypes::NS;
    }

    public function getTarget(): ?string
    {
        return $this->data['target'] ?? null;
    }

}
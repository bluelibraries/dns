<?php

namespace MamaOmida\Dns\Records\Types;

use MamaOmida\Dns\Records\AbstractRecord;
use MamaOmida\Dns\Records\DnsRecordTypes;

class TXT extends AbstractRecord
{

    public function getTypeId(): int
    {
        return DnsRecordTypes::TXT;
    }

    public function getTxt(): ?string
    {
        return $this->raw['txt'] ?? null;
    }

}
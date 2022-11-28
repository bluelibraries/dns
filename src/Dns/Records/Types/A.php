<?php

namespace MamaOmida\Dns\Records\Types;

use MamaOmida\Dns\Records\AbstractRecord;
use MamaOmida\Dns\Records\DnsRecordTypes;

class A extends AbstractRecord
{

    public function getTypeId(): int
    {
        return DnsRecordTypes::A;
    }

    public function getIp(): ?string
    {
        return $this->raw['ip'] ?? null;
    }

}
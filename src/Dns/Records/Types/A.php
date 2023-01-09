<?php

namespace MamaOmida\Dns\Records\Types;

use MamaOmida\Dns\Records\AbstractRecord;
use MamaOmida\Dns\Records\RecordTypes;

class A extends AbstractRecord
{

    public function getTypeId(): int
    {
        return RecordTypes::A;
    }

    public function getIp(): ?string
    {
        return $this->data['ip'] ?? null;
    }

}

<?php

namespace MamaOmida\Dns\Records\Types;

use MamaOmida\Dns\Records\AbstractRecord;
use MamaOmida\Dns\Records\RecordTypes;

class HInfo extends AbstractRecord
{
    public function getTypeId(): int
    {
        return RecordTypes::HINFO;
    }

    public function getHardware(): ?string
    {
        return $this->data['hardware'] ?? null;
    }

    public function getOperatingSystem(): ?string
    {
        return $this->data['os'] ?? null;
    }

}
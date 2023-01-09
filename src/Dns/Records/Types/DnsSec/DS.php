<?php

namespace MamaOmida\Dns\Records\Types\DnsSec;

use MamaOmida\Dns\Records\AbstractRecord;
use MamaOmida\Dns\Records\RecordTypes;

class DS extends AbstractRecord
{
    public function getTypeId(): int
    {
        return RecordTypes::DS;
    }

    public function getDigestType(): ?string
    {
        return $this->data['algorithm-digest'] ?? null;
    }

    public function getKeyTag(): ?int
    {
        return $this->data['key-tag'] ?? null;
    }

    public function getAlgo(): ?int
    {
        return $this->data['algorithm'] ?? null;
    }

    public function getDigest(): ?string
    {
        return $this->data['digest'] ?? null;
    }

}

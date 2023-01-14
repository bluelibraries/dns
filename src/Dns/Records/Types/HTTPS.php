<?php

namespace MamaOmida\Dns\Records\Types;

use MamaOmida\Dns\Records\AbstractRecord;
use MamaOmida\Dns\Records\RecordTypes;

class HTTPS extends AbstractRecord
{
    public function getTypeId(): int
    {
        return RecordTypes::HTTPS;
    }

    public function getSeparator(): ?string
    {
        return $this->data['separator'] ?? null;
    }

    public function getOriginalLength(): ?int
    {
        return $this->data['original-length'] ?? null;
    }

    public function getData(): ?string
    {
        return $this->data['data'] ?? null;
    }

}
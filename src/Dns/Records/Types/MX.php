<?php

namespace MamaOmida\Dns\Records\Types;

use MamaOmida\Dns\Records\AbstractRecord;
use MamaOmida\Dns\Records\RecordTypes;

class MX extends AbstractRecord
{

    public function getTypeId(): int
    {
        return RecordTypes::MX;
    }

    public function getTarget(): ?string
    {
        return $this->data['target'] ?? null;
    }

    public function getPriority(): ?int
    {
        return $this->data['pri'] ?? null;
    }


}
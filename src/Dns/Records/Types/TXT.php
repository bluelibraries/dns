<?php

namespace BlueLibraries\Dns\Records\Types;

use BlueLibraries\Dns\Records\AbstractRecord;
use BlueLibraries\Dns\Records\RecordTypes;

class TXT extends AbstractRecord
{

    public function __construct(array $data = [])
    {
        if (isset($data['entries'])) {
            unset($data['entries']);
        }
        parent::__construct($data);
    }

    public function getTypeId(): int
    {
        return RecordTypes::TXT;
    }

    public function getTxt(): ?string
    {
        return $this->data['txt'] ?? null;
    }

}
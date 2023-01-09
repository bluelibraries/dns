<?php

namespace MamaOmida\Dns\Records\Types;

use MamaOmida\Dns\Records\AbstractRecord;
use MamaOmida\Dns\Records\RecordTypes;
use MamaOmida\Dns\Records\ExtendedTxtRecords;

class TXT extends AbstractRecord
{

    public function __construct(array $data)
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
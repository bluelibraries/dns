<?php

namespace MamaOmida\Dns\Records\Types\DnsSec;

use MamaOmida\Dns\Records\AbstractRecord;
use MamaOmida\Dns\Records\RecordTypes;

class NSEC extends AbstractRecord
{
    public function getTypeId(): int
    {
        return RecordTypes::NSEC;
    }
}

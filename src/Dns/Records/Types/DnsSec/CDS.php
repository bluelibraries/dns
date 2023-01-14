<?php

namespace MamaOmida\Dns\Records\Types\DnsSec;

use MamaOmida\Dns\Records\AbstractRecord;
use MamaOmida\Dns\Records\RecordTypes;

class CDS extends AbstractRecord
{
    public function getTypeId(): int
    {
        return RecordTypes::CDS;
    }


}
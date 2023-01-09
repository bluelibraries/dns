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
}
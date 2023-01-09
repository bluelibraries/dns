<?php

namespace MamaOmida\Dns\Records\Types;

use MamaOmida\Dns\Records\AbstractRecord;
use MamaOmida\Dns\Records\RecordTypes;

class NAPTR extends AbstractRecord
{

    public function getTypeId(): int
    {
        return RecordTypes::NAPTR;
    }

}



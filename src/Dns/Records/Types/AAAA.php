<?php

namespace MamaOmida\Dns\Records\Types;

use MamaOmida\Dns\Records\AbstractRecord;
use MamaOmida\Dns\Records\RecordTypes;

class AAAA extends AbstractRecord
{

    public function getTypeId(): int
    {
        return RecordTypes::AAAA;
    }

    public function getIPV6(): ?string
    {
        return $this->data['ipv6'] ?? null;
    }

}
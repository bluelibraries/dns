<?php

namespace MamaOmida\Dns\Records\Types\Txt;

use MamaOmida\Dns\Records\ExtendedRecordTrait;
use MamaOmida\Dns\Records\ExtendedTxtRecords;
use MamaOmida\Dns\Records\Types\TXT;

class DomainVerification extends TXT
{

    use ExtendedRecordTrait;

    public function getExtendedTypeName(): ?string
    {
        return ExtendedTxtRecords::DOMAIN_VERIFICATION;
    }

    public function getProvider(): ?string
    {
        return ExtendedTxtRecords::getSiteVerification($this->getTxt());
    }

}

<?php

namespace MamaOmida\Dns\Records\Types\Txt;

use MamaOmida\Dns\Records\ExtendedRecordTrait;
use MamaOmida\Dns\Records\ExtendedTxtRecords;
use MamaOmida\Dns\Records\TXTValuesRecordsTrait;
use MamaOmida\Dns\Records\Types\TXT;
use MamaOmida\Dns\Regex;

class MtaSts extends TXT
{
    use ExtendedRecordTrait;
    use TXTValuesRecordsTrait;

    public const VERSION = 'v';
    public const ID = 'id';

    private string $txtRegex = Regex::MTA_STS_RECORD;

    private function getExtendedTypeName(): ?string
    {
        return ExtendedTxtRecords::MTA_STS_REPORTING;
    }

    public function getVersion(): ?string
    {
        return $this->getParsedValue(self::VERSION);
    }

    public function getId(): ?string
    {
        return $this->getParsedValue(self::ID);
    }

}

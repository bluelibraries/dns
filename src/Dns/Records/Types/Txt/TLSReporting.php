<?php

namespace MamaOmida\Dns\Records\Types\Txt;

use MamaOmida\Dns\Records\ExtendedRecordTrait;
use MamaOmida\Dns\Records\ExtendedTxtRecords;
use MamaOmida\Dns\Records\TXTValuesRecordsTrait;
use MamaOmida\Dns\Records\Types\TXT;
use MamaOmida\Dns\Regex;

class TLSReporting extends TXT
{
    use ExtendedRecordTrait;
    use TXTValuesRecordsTrait;

    public const VERSION = 'v';
    public const RUA = 'rua';

    private string $txtRegex = Regex::TLS_REPORTING;

    private function getExtendedTypeName(): ?string
    {
        return ExtendedTxtRecords::TLS_REPORTING;
    }

    public function getVersion(): ?string
    {
        return $this->getParsedValue(self::VERSION);
    }

    public function getRua(): ?string
    {
        return $this->getParsedValue(self::RUA);
    }

}

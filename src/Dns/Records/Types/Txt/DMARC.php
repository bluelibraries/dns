<?php

namespace MamaOmida\Dns\Records\Types\Txt;

use MamaOmida\Dns\Records\ExtendedRecordTrait;
use MamaOmida\Dns\Records\ExtendedTxtRecords;
use MamaOmida\Dns\Records\TXTValuesRecordsTrait;
use MamaOmida\Dns\Records\Types\TXT;
use MamaOmida\Dns\Regex;

class DMARC extends TXT
{
    use ExtendedRecordTrait;
    use TXTValuesRecordsTrait;

    public const VERSION = 'v';
    public const POLICY = 'p';
    public const PERCENTAGE = 'pct';
    public const RUA = 'rua';
    public const RUF = 'ruf';
    public const FO = 'fo';
    public const ASPF = 'aspf';
    public const ADKIM = 'adkim';
    public const REPORT_FORMAT = 'rf';
    public const REPORT_INTERVAL = 'ri';
    public const SUBDOMAIN_POLICY = 'sp';

    private string $txtRegex = Regex::DMARC;

    private function getExtendedTypeName(): ?string
    {
        return ExtendedTxtRecords::DMARC;
    }

    public function getVersion(): ?string
    {
        return $this->getParsedValue(self::VERSION);
    }

    public function getPolicy(): ?string
    {
        return $this->getParsedValue(self::POLICY);
    }

    public function getPercentage(): ?int
    {
        return $this->getIntegerParsedValue(self::PERCENTAGE);
    }

    public function getRua(): ?string
    {
        return $this->getParsedValue(self::RUA);
    }

    public function getRuf(): ?string
    {
        return $this->getParsedValue(self::RUF);
    }

    public function getFo(): ?string
    {
        return $this->getParsedValue(self::FO);
    }

    public function getAspf(): ?string
    {
        return $this->getParsedValue(self::ASPF);
    }

    public function getAdkim(): ?string
    {
        return $this->getParsedValue(self::ADKIM);
    }

    public function getReportFormat(): ?string
    {
        return $this->getParsedValue(self::REPORT_FORMAT);
    }

    public function getReportInterval(): ?int
    {
        return $this->getIntegerParsedValue(self::REPORT_INTERVAL);
    }

    public function getSubdomainPolicy(): ?string
    {
        return $this->getParsedValue(self::SUBDOMAIN_POLICY);
    }

}

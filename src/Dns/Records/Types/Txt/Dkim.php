<?php

namespace MamaOmida\Dns\Records\Types\Txt;

use MamaOmida\Dns\Records\DnsUtils;
use MamaOmida\Dns\Records\ExtendedRecordTrait;
use MamaOmida\Dns\Records\ExtendedTxtRecords;
use MamaOmida\Dns\Records\Types\TXT;
use MamaOmida\Dns\Regex;

class Dkim extends TXT
{
    use ExtendedRecordTrait;

    public const VERSION = 'v';
    public const KEY_TYPE = 'k';
    public const PUBLIC_KEY = 'p';
    public const HASH_TYPE = 'h';
    public const GROUP = 'g';
    public const NOTES = 'n';
    public const QUERY = 'q';
    public const SERVICE_TYPE = 's';
    public const TESTING_TYPE = 't';

    private array $parsedValues = [];

    private function getExtendedTypeName(): ?string
    {
        return ExtendedTxtRecords::DKIM;
    }

    public function parseValues(): bool
    {
        if (empty($this->getTxt())) {
            return false;
        }

        if ($this->isParsedValue()) {
            return true;
        }

        $value = DnsUtils::sanitizeTextLineSeparators($this->getTxt());
        preg_match_all(Regex::DKIM_VALUES, $value, $matches);

        $result = [];

        foreach ($matches[0] as $match) {
            $matchData = explode('=', $match);
            if (!isset($matchData[1])) {
                return false;
            }
            $result[strtolower($matchData[0])] = $matchData[1];
        }

        $this->parsedValues = $result;
        $this->parsedValues['internalHash'] = $this->getValueHash();

        return true;
    }

    /**
     * @return string
     */
    private function getValueHash(): string
    {
        return md5($this->getTxt());
    }

    private function isParsedValue(): bool
    {
        $hash = $this->parsedValues['internalHash'] ?? null;

        if (is_null($hash)) {
            return false;
        }

        return $hash === $this->getValueHash();
    }

    private function getParsedValue(string $key): ?string
    {
        $this->parseValues();
        return $this->parsedValues[$key] ?? null;
    }

    public function getPublicKey(): ?string
    {
        return $this->getParsedValue(self::PUBLIC_KEY);
    }

    public function getVersion(): ?string
    {
        return $this->getParsedValue(self::VERSION);
    }

    public function getKeyType(): ?string
    {
        return $this->getParsedValue(self::KEY_TYPE);
    }

    public function getHashType(): ?string
    {
        return $this->getParsedValue(self::HASH_TYPE);
    }

    public function getGroup(): ?string
    {
        return $this->getParsedValue(self::GROUP);
    }

    public function getNotes(): ?string
    {
        return $this->getParsedValue(self::NOTES);
    }

    public function getQuery(): ?string
    {
        return $this->getParsedValue(self::QUERY);
    }

    public function getServiceType(): ?string
    {
        return $this->getParsedValue(self::SERVICE_TYPE);
    }

    public function getTestingType(): ?string
    {
        return $this->getParsedValue(self::TESTING_TYPE);
    }

    public function getSelector(): ?string
    {
        if (empty($this->getHost())) {
            return null;
        }

        $result = preg_match(Regex::DKIM_SELECTOR_VALUE, $this->getHost(), $matches);

        if ($result !== 1) {
            return null;
        }

        return $matches[1] ?? null;
    }

}

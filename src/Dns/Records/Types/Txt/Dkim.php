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

    private array $parsedValues = [];

    private function getExtendedTypeName(): ?string
    {
        return ExtendedTxtRecords::DKIM;
    }

    private function parseValues()
    {
        if (empty($this->getTxt())) {
            return false;
        }

        if ($this->isParsedValue()) {
            return true;
        }

        $value = DnsUtils::sanitizeTextLineSeparators($this->getTxt());
        $result = preg_match_all(Regex::DKIM_VALUES, $value, $matches);

        if ($result < 2 || empty($matches[0])) {
            return false;
        }
        $result = [];
        foreach ($matches[0] as $match) {
            $matchData = explode('=', $match);
            if (!isset($matchData[1])) {
                return false;
            }
            $result[$matchData[0]] = $matchData[1];
        }

        $this->parsedValues = $result;
        $this->parsedValues['internalHash'] = $this->getValueHash();

        return $result;
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

    public function getParsedValue(string $key): ?string
    {
        $this->parseValues();
        return $this->parsedValues[$key] ?? null;
    }

    public function getPublicKey(): ?string
    {
        return $this->getParsedValue('p');
    }

    public function getVersion(): ?string
    {
        return $this->getParsedValue('v');
    }

    public function getKeyType(): ?string
    {
        return $this->getParsedValue('k');
    }

    public function getHashType(): ?string
    {
        return $this->getParsedValue('h');
    }

    public function getGroup(): ?string
    {
        return $this->getParsedValue('g');
    }

    public function getNotes(): ?string
    {
        return $this->getParsedValue('n');
    }

    public function getQuery(): ?string
    {
        return $this->getParsedValue('q');
    }

    public function getServiceType(): ?string
    {
        return $this->getParsedValue('s');
    }

    public function getTestingType(): ?string
    {
        return $this->getParsedValue('t');
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

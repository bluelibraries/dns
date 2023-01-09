<?php

namespace MamaOmida\Dns\Records\Types\Txt;

use MamaOmida\Dns\Records\DnsUtils;
use MamaOmida\Dns\Records\ExtendedRecordTrait;
use MamaOmida\Dns\Records\ExtendedTxtRecords;
use MamaOmida\Dns\Records\Types\TXT;
use MamaOmida\Dns\Regex;

/**
 * Sender Policy Framework
 */
class SPF extends TXT
{

    use ExtendedRecordTrait;

    public function __construct(array $data)
    {
        if (!empty($data['txt'])) {
            $data['txt'] = DnsUtils::sanitizeTextLineSeparators($data['txt']);
        }
        $data['type'] = $this->getExtendedTypeName();
        parent::__construct($data);
    }

    private function getExtendedTypeName(): ?string
    {
        return ExtendedTxtRecords::SPF;
    }

    public function getHosts(): array
    {
        if (empty($this->getTxt())) {
            return [];
        }

        $regexResult = preg_match_all(Regex::WORDS_SEPARATED_SPACE, $this->getTxt(), $matches);

        if ($regexResult === false) {
            return [];
        }

        $totalMatches = count($matches);

        if ($totalMatches !== 1) {
            return [];
        }

        $words = $matches[0];
        $wordCount = count($words);

        if ($wordCount === 0) {
            return [];
        }

        if ($words[0] !== 'v=spf1') {
            return [];
        }

        array_shift($words);

        return $words;
    }

}

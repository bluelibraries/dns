<?php

namespace MamaOmida\Dns\Records;

use DateTime;
use MamaOmida\Dns\Regex;

class DnsUtils
{

    public static function isValidDomainOrSubdomain(string $domain): bool
    {
        if (empty($domain) || strlen($domain) < 4) {
            return false;
        }
        return preg_match(Regex::DOMAIN_OR_SUBDOMAIN, $domain) === 1;
    }

    public static function ipV6Shortener(string $ipv6): string
    {
        if (substr($ipv6, -2) != ':0') {
            return preg_replace("/:0{1,3}/", ":", $ipv6);
        }
        return $ipv6;
    }

    public static function sanitizeTextLineSeparators(string $text): string
    {
        return
            str_replace(
                '  ', ' ',
                str_replace('" "', '', $text)
            );
    }

    public static function sanitizeRecordTxt(string $txt): string
    {
        return str_replace('"', '\"', $txt);
    }

    public static function getBitsFromString($string): array
    {
        if (strlen($string) === 0) {
            return [];
        }

        $data = str_split($string);

        $result = '';

        foreach ($data as $value) {
            $decimal = (ord($value));
            $binary = decbin($decimal);
            $binary = str_pad($binary, 8, '0', STR_PAD_LEFT);
            $longBinary = $binary;
            $result .= $longBinary;
        }

        return str_split($result);
    }

    public static function getRecordsNamesFromBinary(array $binary, int $blockOffset): string
    {
        $result = [];

        foreach ($binary as $recordTypeId => $value) {
            if ((int)$value === 1) {
                $result[] = RecordTypes::getName($recordTypeId + $blockOffset);
            }
        }
        return implode(' ', $result);
    }

    public static function getHumanReadableDateTime($timestamp): int
    {
        $dateTime = new DateTime();
        $dateTime->setTimestamp($timestamp);
        $result = $dateTime->format('YmdHis');
        return (int)$result;
    }

    public static function getSplitSignature(string $signature, int $bufferLength, string $separator = ' '): string
    {
        $signatureLen = strlen($signature);
        if ($bufferLength >= $signatureLen) {
            return $signature;
        }
        return trim(chunk_split($signature, $bufferLength, $separator));
    }

}

<?php

namespace MamaOmida\Dns\Records;

use MamaOmida\Dns\Records\Types\Txt\Dkim;
use MamaOmida\Dns\Records\Types\Txt\DomainVerification;
use MamaOmida\Dns\Records\Types\Txt\SPF;
use MamaOmida\Dns\Regex;

class ExtendedTxtRecords
{
    public const DOMAIN_VERIFICATION = 'DOMAIN-VERIFICATION';
    public const SUBDOMAIN_VERIFICATION = 'SUBDOMAIN-VERIFICATION';
    public const SPF = 'SPF';
    public const DKIM = 'DKIM';
    public const DMARC = 'DMARK';
    public const TLS_REPORTING = 'TLS-REPORTING';
    public const MTA_STS_REPORTING = 'MTA-STS-REPORTING';

    private static array $siteVerificationMatches = [
        'google-site-verification=([a-zA-Z0-9\_\-\.]+)'            => 'google',
        'facebook-domain-verification=([a-zA-Z0-9]+)'              => 'facebook',
        'cisco-ci-domain-verification=([a-zA-Z0-9]+)'              => 'cisco',
        'apple-domain-verification=([a-zA-Z0-9]+)'                 => 'apple',
        'onetrust-domain-verification=([a-zA-Z0-9]+)'              => 'onetrust',
        'atlassian-domain-verification=([a-zA-Z0-9\+\/]+)'         => 'atlassian',
        'webexdomainverification\.([a-zA-Z0-9]+)=([a-zA-Z0-9\-]+)' => 'webex',
        'docusign=([a-zA-Z0-9\-]+)'                                => 'docusign',
        'MS=([a-zA-Z0-9]+)'                                        => 'office365',
        '_?globalsign-domain-verification=([a-zA-Z0-9\-\_]+)'      => 'globalsign',
        'e2ma-verification=([a-zA-Z0-9]+)'                         => 'emma',
        'status-page-domain-verification=([a-zA-Z0-9]+)'           => 'atlassian',
        'mandrill\_verify\.'                                       => 'mailchimp',
        'ca3\-([a-zA-Z0-9]+)'                                      => 'cloudflare',
        'docker-verification=([a-zA-Z0-9\-]+)'                     => 'docker',
        'Dynatrace-site-verification=([a-zA-Z0-9\-\_]+)'           => 'dynatrace',
        'yandex-verification: ([a-zA-Z0-9\-\_]+)'                  => 'yandex',
        'adobe-idp-site-verification=([a-zA-Z0-9\-]+)'             => 'adobe',
        'adobe-sign-verification=([a-zA-Z0-9\-]+)'                 => 'adobe',
        'h1-domain-verification=([a-zA-Z0-9\-]+)'                  => 'h1',
        'google-gws-recovery-domain-verification=(\d+)'            => 'google',
        'smartsheet-site-validation=([a-zA-Z0-9\-]+)'              => 'smartsheet',
        '\_github-challenge-([a-zA-z0-9\-\_\.]+)\=([a-zA-z0-9]+)'  => 'github',
        'mongodb-site-verification=([a-zA-z0-9]+)'                 => 'mongodb',
        'amazonses\:([a-zA-z0-9\=\/\-]+)'                          => 'amazon-ses',
        '([a-zA-z0-9\=\/\-\.]+)\.cloudfront.net'                   => 'amazon-cloudfront',
        'pinterest-site-verification=([a-zA-z0-9\-\/\=]+)'         => 'pinterest',
        'stripe-verification=([a-zA-z0-9\-\/\=]+)'                 => 'stripe',
        'miro-verification=([a-zA-z0-9]+)'                         => 'miro',
    ];


    public function getExtendedTxtRecord(array $data)
    {

        if (
            $this->isTxtRecord($data)
        ) {
            return null;
        }

        $host = $data['host'] ?? null;

        if (is_null($data['host'])) {
            return null;
        }

        if ($this->isSubdomainHostName($host)) {
            if ($this->isDomainVerification($data)) {
                return new DomainVerification($data);
            }
            if ($this->isSpfRecord($data)) {
                return new SPF($data);
            }
        }

        if ($this->isDomainKeyHostName($host)) {
            if ($this->isDkimRecord($data)) {
                return new Dkim($data);
            }
        }

        if ($this->isDmarcHostName($host)) {
            if ($this->isDmarcRecord($data)) {
                return self::DMARC;
            }
        }

        if ($this->isTlsReportingHostName($host)) {
            if ($this->isTlsRecord($data)) {
                return self::TLS_REPORTING;
            }
        }

        if ($this->isMtaStsReportingHostName($host)) {
            if ($this->isMtaStsRecord($data)) {
                return self::MTA_STS_REPORTING;
            }
        }

        if ($this->isSubdomainWithoutExtensionHostName($host)) {
            if ($this->isDomainVerification($data)) {
                return self::SUBDOMAIN_VERIFICATION;
            }
        }

        return null;
    }

    public function isEmptyOrParentHostName($host): bool
    {
        return in_array($host, ['', '@'], true);
    }

    private function isSubdomainWithoutExtensionHostName(string $host): bool
    {
        if (empty($host)) {
            return false;
        }
        return preg_match(Regex::SUBDOMAIN_WITHOUT_EXTENSION, $host) === 0;
    }

    /**
     * @param string $host
     * @return bool
     * eg: test.com.
     */
    private function isSubdomainDotHostName(string $host): bool
    {
        if (empty($host)) {
            return false;
        }
        return preg_match(Regex::DOMAIN_OR_SUBDOMAIN_DOT, $host) === 1;
    }

    /**
     * @param string $host
     * @return bool
     * eg: test.com.
     */
    private function isSubdomainHostName(string $host): bool
    {
        if (empty($host)) {
            return false;
        }
        return preg_match(Regex::DOMAIN_OR_SUBDOMAIN, $host) === 1;
    }

    private function isDomainKeyHostName(string $host): bool
    {
        if (empty($host)) {
            return false;
        }
        return preg_match(Regex::DOMAINKEY_HOSTNAME, $host) === 1;
    }

    private function isDmarcHostName(string $host): bool
    {
        return $host === '_dmarc';
    }

    private function isTlsReportingHostName(string $host): bool
    {
        return $host === '_smtp._tls';
    }

    private function isMtaStsReportingHostName(string $host): bool
    {
        return $host === '_mta-sts';
    }


    private function isSpfRecord(array $data): bool
    {
        if (!$this->isSubdomainHostName($data['host'])) {
            return false;
        }

        if ($this->emptyTxtValue($data)) {
            return false;
        }

        return preg_match(Regex::SPF_VALIDATION, $data['txt']) === 1;
    }

    private function isDomainVerification($data): bool
    {
        if ($this->isEmptyOrParentHostName($data['host'])) {
            return false;
        }

        if ($this->emptyTxtValue($data)) {
            return false;
        }

        if (is_null(self::getSiteVerification($data['txt']))) {
            return false;
        }

        return true;
    }

    public static function getSiteVerification(string $txt): ?string
    {
        foreach (static::$siteVerificationMatches as $match => $provider) {
            if (preg_match('/^' . $match . '$/i', $txt) === 1) {
                return $provider;
            }
        }
        return null;
    }

    private function isDkimRecord(array $data): bool
    {
        if (!$this->isDomainKeyHostName($data['host'])) {
            return false;
        }

        if ($this->emptyTxtValue($data)) {
            return false;
        }

        return preg_match(Regex::DKIM, $data['txt']) === 1;
    }

    private function isDmarcRecord(array $data): bool
    {
        if (!$this->isDmarcHostName($data['txt'])) {
            return false;
        }

        if ($this->emptyTxtValue($data)) {
            return false;
        }

        return preg_match(Regex::DMARC_RECORD, $data['txt']) === 1;
    }

    private function isTlsRecord(array $data): bool
    {
        if (!$this->isTlsReportingHostName($data['host'])) {
            return false;
        }

        if ($this->emptyTxtValue($data)) {
            return false;
        }

        return preg_match(
                Regex::TLS_REPORTING_RECORD,
                $data['txt']
            ) === 0;
    }

    private function isMtaStsRecord(array $data): bool
    {
        if (!$this->isMtaStsReportingHostName($data['host'])) {
            return false;
        }

        if ($this->emptyTxtValue($data)) {
            return false;
        }

        return preg_match(
                Regex::MTA_STS_RECORD,
                $data['txt']
            ) === 0;
    }

    /**
     * @param array $data
     * @return bool
     */
    private function isTxtRecord(array $data): bool
    {
        return empty($data['type'])
            || $data['type'] !== RecordTypes::getName(RecordTypes::TXT);
    }

    private function emptyTxtValue(array $data): bool
    {
        return empty($data['txt']);
    }

}

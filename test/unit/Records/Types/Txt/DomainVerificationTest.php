<?php

namespace Unit\Records\Types\Txt;

use MamaOmida\Dns\Records\ExtendedTxtRecords;
use MamaOmida\Dns\Records\Types\Txt\DomainVerification;
use MamaOmida\Dns\Test\Unit\Records\AbstractRecordTestClass;

/**
 * @property DomainVerification $subject
 */
class DomainVerificationTest extends AbstractRecordTestClass
{
    public function setUp(): void
    {
        $this->subject = new DomainVerification([]);
        parent::setUp();
    }

    public function testGetTxt()
    {
        $this->assertNull($this->subject->getTxt());
    }

    public function testGetIpValue()
    {
        $value = 'random text here';
        $this->subject->setData(['txt' => $value]);
        $this->assertSame($value, $this->subject->getTxt());
    }

    public function testToStringDefault()
    {
        $this->assertSame('0 IN TXT', $this->subject->toString());
    }

    public function testToStringComplete()
    {
        $this->subject->setData(
            [
                'ttl'  => 7200,
                'host' => 'test.com',
                'txt'  => 'text here'
            ]
        );
        $this->assertSame('test.com 7200 IN TXT "text here"', $this->subject->toString());
    }

    public function testToStringCompleteWithChaosClass()
    {
        $this->subject->setData(
            [
                'ttl'   => 7200,
                'class' => 'CH',
                'host'  => 'test.com',
                'txt'   => 'text here'
            ]
        );
        $this->assertSame('test.com 7200 CH TXT "text here"', $this->subject->toString());
    }

    public function testGetEmptyText()
    {
        $this->subject->setData(
            [
                'ttl'   => 7200,
                'class' => 'IN',
                'host'  => 'test.com',
                'txt'   => ''
            ]
        );
        $this->assertSame('test.com 7200 IN TXT ""', $this->subject->toString());
    }

    public function testGetExtendedTypeName()
    {
        $this->assertSame(ExtendedTxtRecords::DOMAIN_VERIFICATION, $this->subject->getTypeName());
    }

    public function domainVerificationValidDataProvider(): array
    {
        return [
            ['google-site-verification=Mama-._Omida10', 'google'],
            ['facebook-domain-verification=MamaOmida10', 'facebook'],
            ['cisco-ci-domain-verification=MamaOmida10', 'cisco'],
            ['apple-domain-verification=MamaOmida10', 'apple'],
            ['onetrust-domain-verification=MamaOmida10', 'onetrust'],
            ['atlassian-domain-verification=MamaOmida+/10', 'atlassian'],
            ['webexdomainverification.MamaOmida=MamaOmida10-', 'webex'],
            ['docusign=Mama-Omida', 'docusign'],
            ['MS=MamaOmida1', 'office365'],
            ['globalsign-domain-verification=MamaOmida-10_', 'globalsign'],
            ['e2ma-verification=MamaOmida10', 'emma'],
            ['status-page-domain-verification=MamaOmida10', 'atlassian'],
            ['mandrill_verify.', 'mailchimp'],
            ['ca3-MamaOmida10', 'cloudflare'],
            ['docker-verification=Mama-Omida10', 'docker'],
            ['Dynatrace-site-verification=MamaOmida-_10', 'dynatrace'],
            ['yandex-verification: Mama-_Omida10', 'yandex'],
            ['adobe-idp-site-verification=Mama-Omida10', 'adobe'],
            ['adobe-sign-verification=Mama-Omida10', 'adobe'],
            ['h1-domain-verification=Mama-Omida10', 'h1'],
            ['google-gws-recovery-domain-verification=2405', 'google'],
            ['smartsheet-site-validation=Mama-Omida10', 'smartsheet'],
            ['_github-challenge-Ana-Are_Mere-10=AreSi10Pere', 'github'],
            ['mongodb-site-verification=MamaOmida10', 'mongodb'],
            ['amazonses:MamaOmida=-/10', 'amazon-ses'],
            ['MamaOmida10-=./.cloudfront.net', 'amazon-cloudfront'],
            ['pinterest-site-verification=Mama-Omida10=', 'pinterest'],
            ['stripe-verification=Mama-Omida10=', 'stripe'],
            ['miro-verification=MamaOmida10', 'miro'],
            ['grive1ol-verification', null],
        ];
    }

    /**
     * @param string $txt
     * @param ?string $expectedProvider
     * @dataProvider domainVerificationValidDataProvider
     * @return void
     */
    public function testGetProviderValidProviders(string $txt, ?string $expectedProvider)
    {

        $this->subject->setData(
            [
                'ttl'   => 7200,
                'class' => 'IN',
                'host'  => 'test.com',
                'txt'   => $txt
            ]
        );
        $this->assertSame($this->subject->getProvider(), $expectedProvider);
    }

    public function domainVerificationValidDataValuesProvider(): array
    {
        return [
            ['google-site-verification=Mama-._Omida10', 'Mama-._Omida10'],
            ['facebook-domain-verification=MamaOmida10', 'MamaOmida10'],
            ['cisco-ci-domain-verification=MamaOmida10', 'MamaOmida10'],
            ['apple-domain-verification=MamaOmida10', 'MamaOmida10'],
            ['onetrust-domain-verification=MamaOmida10', 'MamaOmida10'],
            ['atlassian-domain-verification=MamaOmida+/10', 'MamaOmida+/10'],
            ['webexdomainverification.MamaOmida=MamaOmida10-', 'MamaOmida10-'],
            ['docusign=Mama-Omida', 'Mama-Omida'],
            ['MS=MamaOmida1', 'MamaOmida1'],
            ['globalsign-domain-verification=MamaOmida-10_', 'MamaOmida-10_'],
            ['e2ma-verification=MamaOmida10', 'MamaOmida10'],
            ['status-page-domain-verification=MamaOmida10', 'MamaOmida10'],
            ['mandrill_verify.', 'mandrill_verify.'],
            ['ca3-MamaOmida10', 'MamaOmida10'],
            ['docker-verification=Mama-Omida10', 'Mama-Omida10'],
            ['Dynatrace-site-verification=MamaOmida-_10', 'MamaOmida-_10'],
            ['yandex-verification: Mama-_Omida10', 'Mama-_Omida10'],
            ['adobe-idp-site-verification=Mama-Omida10', 'Mama-Omida10'],
            ['adobe-sign-verification=Mama-Omida10', 'Mama-Omida10'],
            ['h1-domain-verification=Mama-Omida10', 'Mama-Omida10'],
            ['google-gws-recovery-domain-verification=2405', '2405'],
            ['smartsheet-site-validation=Mama-Omida10', 'Mama-Omida10'],
            ['_github-challenge-Ana-Are_Mere-10=AreSi10Pere', 'AreSi10Pere'],
            ['mongodb-site-verification=MamaOmida10', 'MamaOmida10'],
            ['amazonses:MamaOmida=-/10', 'MamaOmida=-/10'],
            ['MamaOmida10-=./.cloudfront.net', 'MamaOmida10-=./'],
            ['pinterest-site-verification=Mama-Omida10=', 'Mama-Omida10='],
            ['stripe-verification=Mama-Omida10=', 'Mama-Omida10='],
            ['miro-verification=MamaOmida10', 'MamaOmida10'],
            ['grive1ol-verification', 'grive1ol-verification'],
        ];
    }

    /**
     * @param string $txt
     * @param ?string $expectedValue
     * @dataProvider domainVerificationValidDataValuesProvider
     * @return void
     */
    public function testGetProviderValidProvidersValues(string $txt, ?string $expectedValue)
    {

        $this->subject->setData(
            [
                'ttl'   => 7200,
                'class' => 'IN',
                'host'  => 'test.com',
                'txt'   => $txt
            ]
        );
        $this->assertSame($this->subject->getValue(), $expectedValue);
    }

}

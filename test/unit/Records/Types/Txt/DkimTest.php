<?php

namespace Unit\Records\Types\Txt;

use MamaOmida\Dns\Records\ExtendedTxtRecords;
use MamaOmida\Dns\Records\Types\Txt\DKIM;
use MamaOmida\Dns\Test\Unit\Records\AbstractRecordTestClass;

/**
 * @property DKIM $subject
 */
class DKIMTest extends AbstractRecordTestClass
{
    public function setUp(): void
    {
        $this->subject = new DKIM([]);
        parent::setUp();
    }

    public function testGetTxt()
    {
        $this->assertNull($this->subject->getTxt());
    }

    public function testGetTxtValue()
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
        $this->assertSame(ExtendedTxtRecords::DKIM, $this->subject->getTypeName());
    }

    public function parseValuesDataProvider(): array
    {
        return [
            ['', false],
            ['p', false],
            ['p=publickey', false],
            ['v=DKIM1; ', false],
            ['v=DKIM1; p=publickey', true]
        ];
    }

    /**
     * @param $txt
     * @param $expected
     * @dataProvider parseValuesDataProvider
     * @return void
     */
    public function testParseValues($txt, $expected)
    {
        $this->subject->setData(
            [
                'ttl'   => 7200,
                'class' => 'IN',
                'host'  => 'zacusca.domainkey.test.com',
                'txt'   => $txt
            ]
        );

        $this->assertSame($expected, $this->subject->parseValues());
    }

    public function valuesDataProvider(): array
    {
        return [
            ['', []],
            ['p=publick; ', ['p' => 'publick']],
            ['v=DKIM1; ', ['v' => 'DKIM1']],
            ['v=DKIM1; p=publickey', ['v' => 'DKIM1', 'p' => 'publickey']],
            [
                'v=DKIM1; p=publickey;h=a; g=oo; n=a;q=t;s=x; t=0',
                [
                    'v' => 'DKIM1',
                    'p' => 'publickey',
                    'h' => 'a',
                    'g' => 'oo',
                    'n' => 'a',
                    'q' => 't',
                    's' => 'x',
                    't' => '0'
                ]],
        ];
    }

    private function getKeyValues(): array
    {
        return ['v', 'k', 'p', 'h', 'g', 'n', 'q', 's', 't',];
    }

    /**
     * @param string $txt
     * @param array $expected
     * @dataProvider valuesDataProvider
     * @return void
     */
    public function testValues(string $txt, array $expected)
    {

        $this->subject->setData(
            [
                'ttl'   => 7200,
                'class' => 'IN',
                'host'  => 'zacusca.domainkey.test.com',
                'txt'   => $txt
            ]
        );

        $keyValues = $this->getKeyValues();

        foreach ($keyValues as $key) {
            $expectedValue = $expected[$key] ?? null;


            switch ($key) {

                case DKIM::VERSION:
                    $this->assertSame($expectedValue, $this->subject->getVersion());
                    break;

                case DKIM::KEY_TYPE:
                    $this->assertSame($expectedValue, $this->subject->getKeyType());
                    break;

                case DKIM::PUBLIC_KEY:
                    $this->assertSame($expectedValue, $this->subject->getPublicKey());
                    break;

                case DKIM::HASH_TYPE:
                    $this->assertSame($expectedValue, $this->subject->getHashType());
                    break;

                case DKIM::GROUP:
                    $this->assertSame($expectedValue, $this->subject->getGroup());
                    break;

                case DKIM::NOTES:
                    $this->assertSame($expectedValue, $this->subject->getNotes());
                    break;

                case DKIM::QUERY:
                    $this->assertSame($expectedValue, $this->subject->getQuery());
                    break;

                case DKIM::SERVICE_TYPE:
                    $this->assertSame($expectedValue, $this->subject->getServiceType());
                    break;

                case DKIM::TESTING_TYPE:
                    $this->assertSame($expectedValue, $this->subject->getTestingType());
                    break;

            }
        }
    }

    public function testInvalidSelector()
    {
        $this->subject->setData(
            [
                'ttl'   => 7200,
                'class' => 'IN',
                'host'  => 'zacusca.domainkey.test.com',
                'txt'   => ''
            ]
        );

        $this->assertNull($this->subject->getSelector());
    }

    public function testEmptySelector()
    {
        $this->subject->setData(
            [
                'ttl'   => 7200,
                'class' => 'IN',
                'txt'   => ''
            ]
        );

        $this->assertNull($this->subject->getSelector());
    }

    public function testValidSelector()
    {
        $this->subject->setData(
            [
                'ttl'   => 7200,
                'class' => 'IN',
                'host'  => 'zacusca._domainkey.test.com',
                'txt'   => ''
            ]
        );

        $this->assertSame('zacusca', $this->subject->getSelector());
    }

}

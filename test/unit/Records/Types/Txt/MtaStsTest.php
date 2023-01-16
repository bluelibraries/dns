<?php

namespace Unit\Records\Types\Txt;

use MamaOmida\Dns\Records\ExtendedTxtRecords;
use MamaOmida\Dns\Records\Types\Txt\MtaSts;
use MamaOmida\Dns\Test\Unit\Records\AbstractRecordTestClass;

/**
 * @property MtaSts $subject
 */
class MtaStsTest extends AbstractRecordTestClass
{
    public function setUp(): void
    {
        $this->subject = new MtaSts([]);
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
        $this->assertSame(ExtendedTxtRecords::MTA_STS_REPORTING, $this->subject->getTypeName());
    }

    public function parseValuesDataProvider(): array
    {
        return [
            ['', false],
            ['p', false],
            ['v=DMARC1; ', false],
            ['id=test1234', false],
            ['v=STSv1; ', false],
            ['v=STSv1; rua=', false],
            ['v=STSv;id=test1234', false],
            ['v=STSv1; id=test1234', true]
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
                'host'  => '_mta-sts.test.com',
                'txt'   => $txt
            ]
        );

        $this->assertSame($expected, $this->subject->parseValues());
    }

    public function valuesDataProvider(): array
    {
        return [
            ['', []],
            ['p=reject; ', ['p' => 'reject']],
            ['v=STSv1; ', ['v' => 'STSv1']],
            ['v=STSv1; id=none', ['v' => 'STSv1', 'id' => 'none']],
            [
                'v=STSv1; id=test4321',
                [
                    'v'   => 'STSv1',
                    'id' => 'test4321',
                ]],
        ];
    }

    private function getKeyValues(): array
    {
        return ['v', 'id'];
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
                'host'  => '_mta-sts.test.com',
                'txt'   => $txt
            ]
        );

        $keyValues = $this->getKeyValues();

        foreach ($keyValues as $key) {
            $expectedValue = $expected[$key] ?? null;

            switch ($key) {

                case MtaSts::VERSION:
                    $this->assertSame($expectedValue, $this->subject->getVersion());
                    break;

                case MtaSts::ID:
                    $this->assertSame($expectedValue, $this->subject->getId());
                    break;

            }
        }
    }

}

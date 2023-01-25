<?php

namespace Unit\Records\Types;

use BlueLibraries\Dns\Records\Types\NAPTR;
use BlueLibraries\Dns\Test\Unit\Records\RecordTestClass;

/**
 * @property NAPTR $subject
 */
class NAPTRTest extends RecordTestClass
{
    public function setUp(): void
    {
        $this->subject = new NAPTR([]);
        parent::setUp();
    }

    public function testGetOrder()
    {
        $this->assertNull($this->subject->getOrder());
    }

    public function testGetOrderValue()
    {
        $value = 10;
        $this->subject->setData(['order' => 10]);
        $this->assertSame($value, $this->subject->getOrder());
    }

    public function testGetPreference()
    {
        $this->assertNull($this->subject->getPreference());
    }

    public function testGetPreferenceValue()
    {
        $value = 65535;
        $this->subject->setData(['pref' => $value]);
        $this->assertSame($value, $this->subject->getPreference());
    }

    public function testToStringDefault()
    {
        $this->assertSame('0 IN NAPTR', $this->subject->toString());
    }

    public function testGetFlag()
    {
        $this->assertNull($this->subject->getFlag());
    }

    public function testGetFlagValue()
    {
        $value = 'A';
        $this->subject->setData(['flag' => $value]);
        $this->assertSame($value, $this->subject->getFlag());
    }

    public function testGetServices()
    {
        $this->assertNull($this->subject->getServices());
    }

    public function testGetServicesValue()
    {
        $value = 'TEST';
        $this->subject->setData(['services' => $value]);
        $this->assertSame($value, $this->subject->getServices());
    }

    public function testGetRegex()
    {
        $this->assertNull($this->subject->getRegex());
    }

    public function testGetRegexValue()
    {
        $value = 'regex';
        $this->subject->setData(['regex' => $value]);
        $this->assertSame($value, $this->subject->getRegex());
    }

    public function testGetReplacement()
    {
        $this->assertNull($this->subject->getReplacement());
    }

    public function testGetReplacementValue()
    {
        $value = 'replacement';
        $this->subject->setData(['replacement' => $value]);
        $this->assertSame($value, $this->subject->getReplacement());
    }

    public function testToStringComplete()
    {
        $this->subject->setData(
            [
                'host'        => 'test.com',
                'ttl'         => 3600,
                'order'       => 100,
                'pref'        => 10,
                'flag'        => 'U',
                'services'    => 'SIP+D2U',
                'regex'       => '!^.*$!sip:service@example.com!',
                'replacement' => '.',
            ]
        );
        $this->assertSame('test.com 3600 IN NAPTR 100 10 "U" "SIP+D2U" "!^.*$!sip:service@example.com!" .', $this->subject->toString());
    }

}

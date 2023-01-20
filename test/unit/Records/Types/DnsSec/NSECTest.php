<?php

namespace Unit\Records\Types\DnsSec;

use BlueLibraries\Dns\Records\Types\DnsSec\NSEC;
use BlueLibraries\Dns\Test\Unit\Records\AbstractRecordTestClass;

/**
 * @property NSEC $subject
 */
class NSECTest extends AbstractRecordTestClass
{
    public function setUp(): void
    {
        $this->subject = new NSEC([]);
        parent::setUp();
    }

    public function testGetNextAuthoritativeName()
    {
        $this->assertNull($this->subject->getNextAuthoritativeName());
    }

    public function testGetValueSetNextAuthoritativeName()
    {
        $value = 'test.com';
        $this->subject->setData(['next-authoritative-name' => $value]);
        $this->assertSame($value, $this->subject->getNextAuthoritativeName());
    }

    public function testGetTypes()
    {
        $this->assertNull($this->subject->getTypes());
    }

    public function testGetValueSetTypes()
    {
        $value = 'A AAAA NS SOA TXT';
        $this->subject->setData(['types' => $value]);
        $this->assertSame($value, $this->subject->getTypes());
    }

    public function testToStringDefault()
    {
        $this->assertSame('0 IN NSEC', $this->subject->toString());
    }

    public function testToStringComplete()
    {
        $this->subject->setData(
            [
                'host'                    => 'test.com',
                'ttl'                     => '3600',
                'next-authoritative-name' => 'auth.test.com',
                'types'                   => 'A AAAA NS SOA',

            ]
        );
        $this->assertSame('test.com 3600 IN NSEC auth.test.com A AAAA NS SOA', $this->subject->toString());
    }

}

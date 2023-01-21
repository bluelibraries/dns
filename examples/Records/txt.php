<?php

use BlueLibraries\Dns\Facade\Record;
use BlueLibraries\Dns\Records\Types\TXT;

$txt = new TXT([
    'host' => 'test.com',
    'ttl'  => 3600,
    'txt'  => 'test "txt"'
]);


$txt = new TXT();
$txt->setData([
    'host' => 'test.com',
    'ttl'  => 3600,
    'txt'  => 'test "txt"'
]);


$txt = Record::fromString('test.com 3600 IN TXT "test \"txt\""');
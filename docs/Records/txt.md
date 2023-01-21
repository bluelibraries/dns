# TXT records

## Create

### Set data from constructor
```php
$txt = new TXT([
    'host' => 'test.com',
    'ttl'  => 3600,
    'txt'  => 'test "txt"'
]);

echo 'host = ' . $txt->getHost() . PHP_EOL;
echo 'ttl = ' . $txt->getTtl() . PHP_EOL;
echo 'class = ' . $txt->getClass() . PHP_EOL;
echo 'type name = ' . $txt->getTypeName() . PHP_EOL;
echo 'text = ' . $txt->getTxt() . PHP_EOL;
```
```text
host = test.com
ttl = 3600
class = IN
type name = TXT
text = test "txt"
```

### Set data with a setter
```php
$txt = new TXT();
$txt->setData([
    'host' => 'test.com',
    'ttl'  => 3600,
    'txt'  => 'test "txt"'
]);

echo 'host = ' . $txt->getHost() . PHP_EOL;
echo 'ttl = ' . $txt->getTtl() . PHP_EOL;
echo 'class = ' . $txt->getClass() . PHP_EOL;
echo 'type name = ' . $txt->getTypeName() . PHP_EOL;
echo 'text = ' . $txt->getTxt() . PHP_EOL;
```
```text
host = test.com
ttl = 3600
class = IN
type name = TXT
text = test "txt"
```

### From string
```php
$txt = Record::fromString('test.com 3600 IN TXT "test \"txt\""');

echo 'host = ' . $txt->getHost() . PHP_EOL;
echo 'ttl = ' . $txt->getTtl() . PHP_EOL;
echo 'class = ' . $txt->getClass() . PHP_EOL;
echo 'type name = ' . $txt->getTypeName() . PHP_EOL;
echo 'text = ' . $txt->getTxt() . PHP_EOL;
```
```text
host = test.com
ttl = 3600
class = IN
type name = TXT
text = test "txt"
```

### From initialized array
```php
$txt = Record::fromNormalizedArray([
    'host' => 'test.com',
    'ttl'  => 3600,
    'type' => 'TXT',
    'txt'  => 'test "txt"'
]);

echo 'host = ' . $txt->getHost() . PHP_EOL;
echo 'ttl = ' . $txt->getTtl() . PHP_EOL;
echo 'class = ' . $txt->getClass() . PHP_EOL;
echo 'type name = ' . $txt->getTypeName() . PHP_EOL;
echo 'text = ' . $txt->getTxt() . PHP_EOL;
```
```text
host = test.com
ttl = 3600
class = IN
type name = TXT
text = test "txt"
```

### From Internet
```php
$records = DNS::getRecords('test.com', RecordTypes::TXT);
$txt = $records[1];

echo 'host = ' . $txt->getHost() . PHP_EOL;
echo 'ttl = ' . $txt->getTtl() . PHP_EOL;
echo 'class = ' . $txt->getClass() . PHP_EOL;
echo 'type name = ' . $txt->getTypeName() . PHP_EOL;
echo 'text = ' . $txt->getTxt() . PHP_EOL;
```
```text
host = test.com
ttl = 3599
class = IN
type name = DOMAIN-VERIFICATION
text = test "txt"
```
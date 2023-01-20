# DNS

## Retrieve DNS records

### Retrieve TXT records
```php
$records = DNS::getRecords('test.com', RecordTypes::TXT);
print_r($records);
```
```php
Array
(
    [0] => BlueLibraries\Dns\Records\Types\Txt\DomainVerification Object
        (
            [data:protected] => Array
                (
                    [host] => test.com
                    [ttl] => 3454
                    [class] => IN
                    [type] => TXT
                    [txt] => google-site-verification=kW9t2V_S7WjOX57zq0tP8Ae_WJhRwUcZoqpdEkvuXJk
                )
        )
    [1] => BlueLibraries\Dns\Records\Types\TXT Object
        (
            [data:protected] => Array
                (
                    [host] => test.com
                    [ttl] => 3454
                    [class] => IN
                    [type] => TXT
                    [txt] => 55d34914-636b-4a56-b349-fdb9f2c1eaca
                )
        )
)
```

### Retrieve A (address) records
```php
$records = DNS::getRecords('test.com', RecordTypes::A);
print_r($records);
```
```php
Array
(
    [0] => BlueLibraries\Dns\Records\Types\A Object
        (
            [data:protected] => Array
                (
                    [host] => test.com
                    [ttl] => 3600
                    [class] => IN
                    [type] => A
                    [ip] => 67.225.146.248
                )
        )
)
```

### Retrieve AAAA (IPv6 address) records
```php
$records = DNS::getRecords('test.com', RecordTypes::AAAA);
print_r($records);
```
```php
Array
(
    [0] => BlueLibraries\Dns\Records\Types\AAAA Object
        (
            [data:protected] => Array
                (
                    [host] => test.com
                    [ttl] => 279
                    [class] => IN
                    [type] => AAAA
                    [ipv6] => 2a02:cb48:200::1ae
                )
        )
)
```

### Retrieve Nameserver records
```php
$records = DNS::getRecords('test.com', RecordTypes::NS);
print_r($records);
```
```php
Array
(
    [0] => BlueLibraries\Dns\Records\Types\NS Object
        (
            [data:protected] => Array
                (
                    [host] => test.com
                    [ttl] => 3600
                    [class] => IN
                    [type] => NS
                    [target] => nstest.com
                )
        )
)
```

### Retrieve CNAME records

```php
$records = DNS::getRecords('x.test.com', RecordTypes::CNAME);
print_r($records);
```
```php
Array
(
    [0] => BlueLibraries\Dns\Records\Types\CNAME Object
        (
            [data:protected] => Array
                (
                    [host] => x.test.com
                    [ttl] => 300
                    [class] => IN
                    [type] => CNAME
                    [target] => test.com
                )
        )
)
```

### Retrieve SOA records
```php
$records = DNS::getRecords('test.com', RecordTypes::SOA);
print_r($records);
```
```php
Array
(
    [0] => BlueLibraries\Dns\Records\Types\SOA Object
        (
            [data:protected] => Array
                (
                    [host] => test.com
                    [ttl] => 3600
                    [class] => IN
                    [type] => SOA
                    [mname] => testxsoa.com
                    [rname] => testysoa.com
                    [serial] => 212
                    [refresh] => 10800
                    [retry] => 3600
                    [expire] => 604800
                    [minimum-ttl] => 3600
                )
        )
)
```

### Retrieve MX records
```php
$records = DNS::getRecords('test.com', RecordTypes::MX);
print_r($records);
```
```php
Array
(
    [0] => BlueLibraries\Dns\Records\Types\MX Object
        (
            [data:protected] => Array
                (
                    [host] => test.com
                    [ttl] => 300
                    [class] => IN
                    [type] => MX
                    [pri] => 10
                    [target] => mail.test.com
                )
        )
)
```

# Retrieve CAA (Certification Authority Authorization)
```php
$records = DNS::getRecords('test.com', RecordTypes::CAA);
print_r($records);
```
```php
Array
(
    [0] => BlueLibraries\Dns\Records\Types\CAA Object
        (
            [data:protected] => Array
                (
                    [host] => test.com
                    [ttl] => 3600
                    [class] => IN
                    [type] => CAA
                    [flags] => 0
                    [tag] => issue
                    [value] => digicert.com
                )
        )
)
```


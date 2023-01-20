# DNS

## Use certain DNS handler for DNS interrogation
### This package contains **4** types which can be used for DNS interrogations
1. DnsGetRecord based on `dns_get_record` PHP function
2. Dig based on `dig` shell command (better than `dns_get_record` and still secured)
3. UDP based on `raw` DNS calls using `UDP/socket` - useful for short answered queries as UDP answers might be limited to `512` bytes 
4. TCP based on `raw` DNS calls using `TCP/socket` - <font style="color:#3399FF; font-size:16px;font-weight:bold">this the best</font> and is set as `default` handler

### Retrieve records using `dns_get_record`
```php
$records = DNS::getRecords('test.com', RecordTypes::TXT, DnsHandlerTypes::DNS_GET_RECORD);
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
                    [class] => IN
                    [ttl] => 0
                    [type] => TXT
                    [txt] => google-site-verification=test-636b-4a56-b349-test
                )
        )
)
```

### Retrieve records using `dig`
```php
$records = DNS::getRecords('test.com', RecordTypes::TXT, DnsHandlerTypes::DIG);
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
                    [class] => IN
                    [ttl] => 0
                    [type] => TXT
                    [txt] => google-site-verification=test-636b-4a56-b349-test
                )
        )
)
```

### Retrieve records using `UDP`
```php
$records = DNS::getRecords('test.com', RecordTypes::TXT, DnsHandlerTypes::UDP);
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
                    [class] => IN
                    [ttl] => 0
                    [type] => TXT
                    [txt] => google-site-verification=test-636b-4a56-b349-test
                )
        )
)
```

### Retrieve records using `TCP`
```php
// TCP is the default DNS handler and if you are using it then you can skip it
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
                    [class] => IN
                    [ttl] => 0
                    [type] => TXT
                    [txt] => google-site-verification=test-636b-4a56-b349-test
                )
        )
)
```

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


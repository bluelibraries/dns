# DNS


## Retrieve DNS records

### Retrieve TXT Records

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

### Retrieve Address records

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

### Retrieve Name server records

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

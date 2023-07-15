filterit-php is a PHP library that provides a simple and flexible way to build and parse query strings for web applications. It helps to easily construct and manipulate URL query strings, allowing developers to create complex queries and filter data from various sources.

## Installation:
```bash
composer require filterit/filterit-php
```

## Usage
### Build Query String
```php
require_once 'path/to/FilterIt.php';
require_once 'path/to/Enums/Operator.php';


$filter = new FilterIt();
$filter->addFilter('email', Operator::EndsWith, '.org');

print_r($filter->toQuery());
// "email=ends_with:.org"
```

### Parse Query String
```php
require_once 'path/to/QueryPaser.php';

$queryString = "id=equal:10";

print_r(QueryParser::parseQuery($queryString));

//[
//     'query'         => 'id=equal:10',
//     'isNestedQuery' => false,
//     'column'        => 'id',
//     'operator'      => 'starts_with',
//     'value'         => 'id',
//     'delimiter'     => 'and',
//]
```

filterit-php is a PHP library that provides a simple and flexible way to build and parse query strings for web applications. It helps to easily construct and manipulate URL query strings, allowing developers to create complex queries and filter data from various sources.

## Installation:
```bash
composer require filterit/filterit-php
```

## Usage
### Build Query String
```php
$filter = new FilterIt();
$filter->addFilter('email', Operator::EndsWith, '.org');

print_r($filter->toQuery());
// "email=ends_with:.org"
```

### Parse Query String
```php
print_r(QueryParser::parseQuery('id=equal:10'));

//[
//     'query'         => 'id=equal:10',
//     'isNestedQuery' => false,
//     'column'        => 'id',
//     'operator'      => 'equal',
//     'value'         => '10',
//     'delimiter'     => 'and',
//]
````

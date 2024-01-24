# cache-php
Cache tools for PHP applications

### Memory

Extends [PHP memcached](https://www.php.net/manual/en/book.memcached.php)

#### Init

```
$memory = new \Yggverse\Cache\Memory(

  'localhost',  // memcached server host, localhost by default
  11211,        // memcached server port, 11211 by default

  'my_app',     // application namespace
  3600 + time() // cache time by default
);
```

#### Supported methods

##### Memory::set

##### Memory::delete

##### Memory::flush

##### Memory::get

##### Memory::getByValueCallback

Return cached or cache new value of plain value callback

```
  $value = $memory->getByValueCallback(
    'key_name',    // string, unique key name
    'value',       // mixed, plain value
    3600 + time(), // optional, cache timeout for this value
  );
```

##### Memory::getByMethodCallback

Return cached or cache new value of object method callback

```
  $value = $memory->getByMethodCallback(
    $class_object,         // object of method class
    'method_name',         // object method name
    [
      $method_attribute_1, // optional, array of attributes callback method requires
      $method_attribute_2,
      ...
    ]
    3600 + time(),         // optional, cache timeout for this value
  );
```
# Overloaded Function

PHP actually does not support [function overloading](https://en.wikipedia.org/wiki/Function_overloading), as functions distinguished but name only.
This is a class that helps to imulate function overloading in PHP.

Table of Contents
=================  
* [General usage](#general-usage)  
    - [1. Simple signature](#simple-signature)
    - [2. Union types](#union-types)
    - [3. Intersection types](#intersection-types)
    - [4. Optional parameters](#optional-parameters)

<a name="General usage"></a>

## General usage

Function cases provided as an array of callables, where key is a signature of parameters types.

<a name="simple-signature"></a>

### 1. Simple signature:

Parameters signature is a list of types separeted by comma.

```php
    $func = new OverloadedFunction([
        'integer, integer' => function ($a, $b) { return $a + $b; },
        'string, string' => function ($a, $b) { return $a . $b; }
    ]);

    var_dump($func(1, 1)) // => 2
    var_dump($func('1', '1')) // => '11'
```

<a name="union-types"></a>

### 2. Union types:

Sometimes your parameter need to be of a kind, that implements not one interface, but several.

```php
    $func = new OverloadedFunction([
        'ArrayAccess&Countable' => function ($i) { return true; }
    ]);

    var_dump($func(new ArrayIterator)) // => bool(true)
```

<a name="intersection-types"></a>

### 3. Intersection types:

Sometimes you allow parameter to be not of one type, but of several types.

```php
    $func = new OverloadedFunction([
        'string|integer' => function ($i) { return true; }
    ]);

    var_dump($func(1)) // => bool(true)
    var_dump($func('1')) // => bool(true)
```

<a name="optional-parameters"></a>

### 4. Optional parameters:

Sometimes you allow parameter to be optional. Make sure you provide default values for such cases. 

```php
    $func = new OverloadedFunction([
        '?integer' => function ($i = 1) { return $i; }
    ]);

    var_dump($func(1)) // => bool(true)
    var_dump($func()) // => bool(true)
```

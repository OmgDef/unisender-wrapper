UniSender API wrapper
=====================
UniSender API wrapper

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist omgdef/unisender-wrapper "dev-master"
```

or add

```
"omgdef/unisender-wrapper": "dev-master"
```

to the require section of your `composer.json` file.


Usage
-----

Once the extension is installed, simply use it in your code by  :

```php
$obj = new \omgdef\unisender\UniSenderWrapper();
$obj->testMode = true;
$obj->apiKey = 'some_key_jere';
$response = $obj->sendSms(['phone' => '123123123', 'text' => 'text']);
```

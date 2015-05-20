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

Properties
------------

Property | Description
----------|------------
apiKey | Your API key
senderPhone | Sender's phone number
senderName | Sender's name
senderEmail | Sender's email
testMode | Enable test mode
encoding | Encoding (Default: UTF-8)
timeout | Connection timeout (Default: 10)
retryCount | The number of connection attempts (Default: 0)

Usage
-----

Once the extension is installed, simply use it in your code by  :

```php
$obj = new \omgdef\unisender\UniSenderWrapper();
$obj->testMode = true;
$obj->apiKey = 'some_key_here';
$response = $obj->sendSms(['phone' => '123123123', 'text' => 'text']);
```

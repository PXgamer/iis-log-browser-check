# log-browser-check

A simple web server log checker to monitor the browser diversity on websites.

## Supported logs

Server | Supported?
------ | ----------
IIS    | Yes
Apache | No
Nginx  | No

## Requirements

- PHP 5.6+
- Composer

## Example Usage

```php
<?php
include 'vendor/autoload.php';

use pxgamer\LogBrowserChecker\Config;
use pxgamer\LogBrowserChecker\IIS;

$config = new Config([
    'session_column' => 7,
    'ip_column' => 6,
    'root_dir' => __DIR__ . '/logs',
    'site_name' => 'test.com',
    'ignored_ips' => ['127.0.0.1']
]);

$browserCheck = new IIS($config);
$browserCheck->findFiles();
$browserCheck->execute();
		
$browserCheck->getBrowserStats();
```
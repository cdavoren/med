# MED

An early attempt at a medical quiz testing site.  Uses Symfony (PHP library).

## Example Configuration files

`config/config.php`

```php
<?php

// Application-wide settings
$appConfig = array(

  // Database connection settings
  'database' => array(
    'host' => 'localhost',
    'port' => 3306,
    'user' => 'root',
    'pass' => 'fred',
    'name' => 'med',
    'doctrine_driver' => 'pdo_mysql',
  ),

  // PHPass settings
  'phpass_hash_cost_log2' => 8,
  'phpass_hash_portable'  => false,

  // Application path settings
  'app_server' => 'rubikscomplex.net',
  'app_root'   => '/',

  // SSL settings
  'ssl_enabled' => true,

  // Contacts
  'admin_email' => 'chris@rubikscomplex.net',
);

?>
```
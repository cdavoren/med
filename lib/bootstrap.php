<?php

// Ensure that include path includes this project's lib directory
set_include_path(get_include_path().PATH_SEPARATOR.__DIR__);

// Load configuration settings
require('../config/database.php');
require('../config/security.php');

require('PasswordHash.php');
require('Doctrine/Common/ClassLoader.php');

// Load Doctrine classes
$classLoader = new \Doctrine\Common\ClassLoader($ns='Doctrine', $includePath='/var/develwww/lib');
$classLoader->register();

$symfonyLoader = new \Doctrine\Common\ClassLoader($nd='Symfony', $includePath=__DIR__.'/Doctrine');
$symfonyLoader->register();

// Load model classes
$entityLoader = new \Doctrine\Common\ClassLoader($ns=null, $includePath=__DIR__.'/model/');
$entityLoader->register();

use Doctrine\ORM\Configuration;
use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;

$config = new Configuration();

$connectionParams = array(
    'dbname'   => $databaseConfig['name'],
    'user'     => $databaseConfig['user'],
    'password' => $databaseConfig['pass'],
    'host'     => $databaseConfig['host'],
    'port'     => $databaseConfig['port'],
    'driver'   => 'pdo_mysql',
);

// Establish database connection and create entity manager
$conn = DriverManager::getConnection($connectionParams, $config);
$config = Setup::createYAMLMetadataConfiguration(array('../config/schema'), true);
$em = EntityManager::create($connectionParams, $config);

// Password hasher
$ph = new PasswordHash(
    $phpassSettings['hash_cost_log2'], 
    $phpassSettings['hash_portable'],
);

?>

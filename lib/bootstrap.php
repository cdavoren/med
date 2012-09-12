<?php

// Sessions are a necessity
session_start();

// Load configuration settings
require('../config/config.php');

require('PasswordHash.php');
require('Doctrine/Common/ClassLoader.php');

// Load Doctrine classes
$classLoader = new \Doctrine\Common\ClassLoader($ns='Doctrine', $includePath=__DIR__);
$classLoader->register();

// Load Symfony classes
$symfonyLoader = new \Doctrine\Common\ClassLoader($nd='Symfony', $includePath=__DIR__.'/Doctrine');
$symfonyLoader->register();

// Load model classes
$entityLoader = new \Doctrine\Common\ClassLoader($ns='rubikscomplex', $includePath=__DIR__);
$entityLoader->register();

use Doctrine\ORM\Configuration;
use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;

$config = new Configuration();

$connectionParams = array(
    'dbname'   => $appConfig['database']['name'],
    'user'     => $appConfig['database']['user'],
    'password' => $appConfig['database']['pass'],
    'host'     => $appConfig['database']['host'],
    'port'     => $appConfig['database']['port'],
    'driver'   => $appConfig['database']['doctrine_driver'],
);

// Establish database connection and create entity manager
$conn = DriverManager::getConnection($connectionParams, $config);
$config = Setup::createYAMLMetadataConfiguration(array(__DIR__.'/../config/schema'), true);
$em = EntityManager::create($connectionParams, $config);

// Ensure that the user entity is conveniently available, if one is logged in
$loggedUser = null;
if (isset($_SESSION['userid'])) {
  $loggedUser = $em->find('\rubikscomplex\model\User', $_SESSION['userid']);
}

// Password hasher
$ph = new PasswordHash(
    $appConfig['phpass_hash_cost_log2'], 
    $appConfig['phpass_hash_portable']
);

?>

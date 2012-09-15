<?php

# set_include_path(get_include_path().PATH_SEPARATOR.__DIR__.'/../lib'.PATH_SEPARATOR.__DIR__.'/../lib/Doctrine');

# require('Doctrine/Common/ClassLoader.php');

use Doctrine\ORM\EntityManager,
    Doctrine\ORM\Configuration,
    Doctrine\ORM\Tools\Setup,
    Doctrine\DBAL\DriverManager;


require('../config/config.php');

$entityLoader = new \Doctrine\Common\ClassLoader($ns = 'rubikscomplex', $includePath = '../lib');
$entityLoader->register();

# $config = new Configuration();

$connectionParams = array(
    'dbname'   => $appConfig["database"]['name'],
    'user'     => $appConfig["database"]['user'],
    'password' => $appConfig["database"]['pass'],
    'host'     => $appConfig["database"]['host'],
    'port'     => $appConfig["database"]['port'],
    'driver'   => $appConfig["database"]['doctrine_driver'],
);

# $conn = DriverManager::getConnection($connectionParams, $config);
$config = Setup::createYAMLMetadataConfiguration(array('/var/develwww/config/schema'), true);
$em = EntityManager::create($connectionParams, $config);

// var_dump($em->find('User'));
$helperSet = new \Symfony\Component\Console\Helper\HelperSet(array(
    'db' => new \Doctrine\DBAL\Tools\Console\Helper\ConnectionHelper($em->getConnection()),
    'em' => new \Doctrine\ORM\Tools\Console\Helper\EntityManagerHelper($em)
));

?>

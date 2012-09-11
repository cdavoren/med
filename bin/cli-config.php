<?php

# set_include_path(get_include_path().PATH_SEPARATOR.__DIR__.'/../lib'.PATH_SEPARATOR.__DIR__.'/../lib/Doctrine');

# require('Doctrine/Common/ClassLoader.php');

use Doctrine\ORM\EntityManager,
    Doctrine\ORM\Configuration,
    Doctrine\ORM\Tools\Setup,
    Doctrine\DBAL\DriverManager;


require('../config/database.php');
# require('../lib/entities/User.php');

$entityLoader = new \Doctrine\Common\ClassLoader($ns = null, $includePath = '../lib/entities');
$entityLoader->register();

# $config = new Configuration();

$connectionParams = array(
    'dbname'   => $databaseConfig['name'],
    'user'     => $databaseConfig['user'],
    'password' => $databaseConfig['pass'],
    'host'     => $databaseConfig['host'],
    'port'     => $databaseConfig['port'],
    'driver'   => 'pdo_mysql',
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

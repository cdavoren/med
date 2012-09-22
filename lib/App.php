<?php

require __DIR__.'/Doctrine/Common/ClassLoader.php';
require __DIR__.'/PasswordHash.php';
require __DIR__.'/../config/config.php';

class App {
    private static $initialised = false;
    private static $_config = null;
    private static $_user = null;
    private static $_em = null;
    private static $_ph = null;
    private static $_log = array();

    private static function init() {
        global $appConfig;
        if (self::$initialised) {
            return;
        }

        session_start();

        self::$_config = $appConfig;

        // Load Doctrine classes
        $classLoader = new \Doctrine\Common\ClassLoader($ns='Doctrine', $includePath=__DIR__);
        $classLoader->register();

        // Load Symfony classes
        $symfonyLoader = new \Doctrine\Common\ClassLoader($nd='Symfony', $includePath=__DIR__.'/Doctrine');
        $symfonyLoader->register();

        // Load model classes
        $entityLoader = new \Doctrine\Common\ClassLoader($ns='rubikscomplex', $includePath=__DIR__);
        $entityLoader->register();

        $dconnParams = array(
            'dbname'   => self::$_config['database']['name'],
            'user'     => self::$_config['database']['user'],
            'password' => self::$_config['database']['pass'],
            'host'     => self::$_config['database']['host'],
            'port'     => self::$_config['database']['port'],
            'driver'   => self::$_config['database']['doctrine_driver'],
        );
        /*
        $dconfig = new \Doctrine\ORM\Configuration();
        $dconn = \Doctrine\DBAL\DriverManager::getConnection($dconnParams, $dconfig);
         */
        $dconfig = \Doctrine\ORM\Tools\Setup::createYAMLMetadataConfiguration(array(__DIR__.'/../config/schema'), true);
        self::$_em = \Doctrine\ORM\EntityManager::create($dconnParams, $dconfig);

        if (isset($_SESSION['userid'])) {
            self::$_user = self::$_em->find('\rubikscomplex\model\User', $_SESSION['userid']);
        }

        self::$_ph = new PasswordHash(
            self::$_config['phpass_hash_cost_log2'],
            self::$_config['phpass_hash_portable']
        );

        self::$initialised = true;
    }

    public static function initialize() {
        self::init();
    }

    public static function getConfiguration() {
        self::init();        
        return self::$_config;
    }

    public static function getManager() {
        self::init();
        return self::$_em;
    }

    public static function getUser() {
        self::init();
        return self::$_user;
    }

    public static function getHasher() {
        self::init();
        return self::$_ph;
    }

    public static function log($level, $message) {
        self::init();
        $mtime = explode(" ", microtime());
        $unixtime = int($mtime[0]);
        $msecs = int($mtime[1]);
        self::$_log[] = array(
            'timestamp' => $unixtime,
            'msecs'     => $msecs,
            'level'     => $level, 
            'message'   => $message
        );
    }

    public static function getLog() {
        self::init();
        return self::$_log;
    }

    public static function getHTMLLog($timestampFormat='H:i:s') {
        self::init();
        $html = '';

        foreach(self::$_log as $logEntry) {
            $html .= sprintf(
                '%s.%d : <strong>[ %s ] : </strong> %s<br />', 
                date($timestampFormat, $logEntry['timestamp']), 
                $logEntry['msecs'],
                $logEntry['level'], 
                $logEntry['message']);
        }
        return $html;
    }

    public static function getRelativeRootForPath($path=null) {
        self::init();
        if ($path === null) {
            $path = $_SERVER['REQUEST_URI'];
        }
        $cutoff_root = substr($path, strlen(self::$_config['app_root']));
        $subdir_count = substr_count($cutoff_root, '/');
        return str_repeat('../', $subdir_count);
    }
}
?>

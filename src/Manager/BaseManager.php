<?php
/**
 * Class/file BaseManager.php
 *
 * @author Khachornchit Songsaen
 * Date: 2/16/2019
 * Time: 12:48 PM
 */

namespace App\Manager;

use Doctrine\ORM\Configuration;
use Doctrine\ORM\Tools\Console\ConsoleRunner;
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;

/**
 * Class DatabaseManager
 * @package App\Manager
 */
class BaseManager
{
    protected $configuration;

    /**
     * @var array
     */
    protected $connectionParameters = [];

    /**
     * @var string
     */
    private $dbName;

    /**
     * @var string
     */
    private $dbHost;

    /**
     * @var string
     */
    private $dbPort;

    /**
     * @var string
     */
    private $dbUser;

    /**
     * @var string
     */
    private $dbPassword;

    /**
     * DatabaseManager constructor.
     */
    public function __construct()
    {
        $this->InitDBConfiguration();
        $this->InitDBConnection();
    }

    /**
     * @return array
     */
    private function getUrl()
    {
        return json_encode($this->connectionParameters, true);
    }

    private function InitDBConfiguration()
    {
        $proxies_dir = __DIR__ . '/App/models/proxies';
        $metadata_paths = array(__DIR__ . 'models/Entity');
        $dev_mode = $_SERVER['APP_ENV'];
        $this->configuration = Setup::createAnnotationMetadataConfiguration($metadata_paths, $dev_mode, $proxies_dir, null, false);
    }

    private function InitDBConnection()
    {
        $this->InitParameters();
        $this->connectionParameters = [
            'dbname' => $this->dbName,
            'user' => $this->dbUser,
            'password' => $this->dbPassword,
            'host' => $this->dbHost,
            'driver' => 'pdo_mysql'
        ];
    }

    private function InitParameters()
    {
        // DATABASE_URL=mysql://root:1234@db:3306/microservices
        echo "DATABASE_URL=".$_SERVER['DATABASE_URL'];
        $urls = explode("/", $_SERVER['DATABASE_URL']);
        $userPort = explode(':', $urls[sizeof($urls) - 2]);
        $passwordHost = explode('@', $userPort[1]);

        $this->dbName = $urls[sizeof($urls) - 1];
        $this->dbUser = $userPort[0];
        $this->dbPort = $userPort[2];
        $this->dbPassword = $passwordHost[0];
        $this->dbHost = $passwordHost[1];
    }
}

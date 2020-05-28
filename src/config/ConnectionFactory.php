?php
  
namespace wishlist\conf;

use Illuminate\Database\Capsule\Manager as DB;


class ConnectionFactory {

    private static $config = null;
    private static $db = null;

 public static function setConfig($configfile) {
        self::$config = parse_ini_file($configfile);
        if (is_null(self::$config))
            throw new DBException("config file could not be parsed\n");
    }

 public static function makeConnection(){
        $db = new DB();
        $db->addConnection( [
            'driver'    =>  self::$config['db_driver'],
            'host'      =>  self::$config['host'],
            'database'  =>  self::$config['dbname'],
            'username'  =>  self::$config['db_user'],
            'password'  =>  self::$config['db_password'],
            'charset'   =>  self::$config['db_charset'],
            'collation' =>  self::$config['db_collation'],
            'prefix'    =>  ''
        ]);

        $db->setAsGlobal();
        $db->bootEloquent();

        return self::$db;
    }
	
}


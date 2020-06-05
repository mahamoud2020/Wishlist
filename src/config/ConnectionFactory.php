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
            'driver'    =>  self::$config['db_mysql'],
            'host'      =>  self::$config['db_host'],
            'database'  =>  self::$config['db_database'],
            'username'  =>  self::$config['db_newuser'],
            'password'  =>  self::$config['db_wampp'],
            'charset'   =>  self::$config['db_charset'],
            'collation' =>  self::$config['db_collation'],
            
        ]);

        $db->setAsGlobal();
        $db->bootEloquent();

        return self::$db;
    }
	
}


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


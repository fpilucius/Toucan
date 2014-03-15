<?php
namespace Toucan\Component\Orm;

use Toucan\Component\Registre\Registry;

class Orm {
    
    public static $conn;
    
    public static $mode = \PDO::FETCH_OBJ;
    
    public static function connection()
    {
        $appconfig = Registry::get('container');
        if (!isset(self::$conn)) {
            $adapter = ucfirst($appconfig->getService('config')->get('db_engine'));
            $classNameAdapter = 'Toucan\\Component\\Orm\\Adapter\\' . $adapter;
            self::$conn = new $classNameAdapter();
        }
        return self::$conn;
    }
    
    public static function setFetchMode($mode = '')
    {
        if ($mode == 'FETCH_NUM') {
            self::$mode = \PDO::FETCH_NUM;
        } elseif ($mode == 'FETCH_ASSOC') {
            self::$mode = \PDO::FETCH_ASSOC;
        }
    }    
}
?>

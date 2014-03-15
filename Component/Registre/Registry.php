<?php
namespace Toucan\Component\Registre;

class Registry
{
    private static $_container = array();
    
    public static function set($offset, $value)
    {
         if (!self::isRegister($offset)) {
            self::$_container[$offset] = $value;
        }
    }
    
    public static function get($offset)
    {
        return isset(self::$_container[$offset]) ? self::$_container[$offset] : null;
    }
    
    public static function isRegister($offset)
    {
        return isset(self::$_container[$offset]);
    }
    
    public static function unRegister($param)
    {
        unset(self::$_container[$offset]);
    }
    
    public static function getRegister()
    {
        return self::$_container;
    }
}
?>

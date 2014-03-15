<?php
namespace Toucan\Component\Orm;

use Toucan\Component\Orm\Relation\HasOne;
use Toucan\Component\Orm\Relation\HasMany;
use Toucan\Component\Orm\Query;

class Relation
{
    const ONE = 1;

    const MANY = 2;
    
    public static $relations = array();
    
    protected $collection = array();
    
    public static function generate($id = null)
    { 
        if($id != null && is_numeric($id)) {
            $query = new Query();
            $result = $query->from($obj)->where('id = ?', $id);
           $this->collection = $result; 
        }
        
        foreach (self::$relations as $key => $val) {
            switch ($key) {
                case 1:
                    $this->collection = HasOne::generate($id = null, $val); 
                    break;
                case 2:
                    $this->collection = HasMany::generate($id = null, $val);
                    break;
            }
        }
       
   	       return (object) $this->collection;
        
    }
}
?>

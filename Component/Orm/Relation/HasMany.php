<?php

namespace Toucan\Component\Orm\Relation;

use Toucan\Component\Orm\Query;

class HasMany
{
    protected $collection;

    public static function generate($id = null, $val)
    {
        $return = array();
        $query = new Query();
        if($object->getId() != null) {
            $query->from($val['relationTable'])
                    ->where($val['foreign'].' = ?', $id);
            $result = $query->execute();
        } else {
            
        }
        $return[$val['relationTable']] = new \ArrayObject($result);
        return $return;
    }

}
?>
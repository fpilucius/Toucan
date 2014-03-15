<?php
namespace Toucan\Component\Orm;

use Toucan\Component\Orm\Convention\CrudInterface;
use Toucan\Component\Orm\Convention\TransactionInterface;
use Toucan\Component\Orm\Query;
use Toucan\Component\Orm\Table\TableCount;

class Table implements CrudInterface, TransactionInterface 
{
    protected $pk = 'id';
    
    protected $query;
    
    public function __construct()
    {
        $this->query = new Query();
    }
    /**
     *
     * @param string $object
     * @param array $option Option (fields, array conditions, order, limit, offset->defaut=0,
     * relation => array(class, local, foreign, refClass, associate)
     * 
     */
    public function read($object, array $options = null)
    {
        $query = $this->query;
        $query->from($object);

        if (isset($options['fields'])) {
            $query->select($options['fields']);
        }
        if (isset($options['relation'])) {
            extract($options['relation']);
            $class = strtolower(preg_replace('/([A-Z]+)([A-Z])/','\1_\2', preg_replace('/([a-z\d])([A-Z])/','\1_\2', strval($class))));
            if (isset($refClass)) {
                $refClass = strtolower(preg_replace('/([A-Z]+)([A-Z])/','\1_\2', preg_replace('/([a-z\d])([A-Z])/','\1_\2', strval($refClass))));
                if(isset($associate)) {
                    $associate = strtolower(preg_replace('/([A-Z]+)([A-Z])/','\1_\2', preg_replace('/([a-z\d])([A-Z])/','\1_\2', strval($associate))));
                    $query->innerJoin($associate, $object . '.' . $associate . '_id = ' . $associate .'.id');
                }
                $query->innerJoin($refClass, $object . '.id = ' . $refClass . '.' . $object . '_id');
                $query->innerJoin($class, $refClass . '.' . $class . '_id = ' . $class . '.id');
            } else {
                $query->innerJoin($class, $object . '.'.$local . ' = ' . $class . '.' . $foreign);
            }
        }
        if(isset($options['whereIn'])) {
            $key = array_keys($options['whereIn']);
            $val = array_values($options['whereIn']);
            $query->whereIn($key[0], $val[0]);
        }
        if (isset($options['conditions']) && is_array($options['conditions'])) {
            $key = array_keys($options['conditions']);
            $val = array_values($options['conditions']);
            $nbCond = count($options['conditions']);
            if ($nbCond == 1) {
                $query->where($key[0], $val[0]);
            } else {
                $query->where($key[0], $val[0]);
                for ($i = 1; $i < $nbCond; ++$i) {
                    $query->andWhere($key[$i], $val[$i]);
                }
            }
        }

        if (isset($options['order'])) {
            $query->order($options['order']);
        }

        if (!isset($options['offset'])) {
            $offset = 0;
        } else {
            $offset = $options['offset'];
        }

        if (isset($options['limit'])) {
            $query->limit($options['limit'], $offset);
        }
        //var_dump($query->getSql());
        return $query->execute();
    }
    
    /**
     *
     * @param string $object
     * @param array $data 
     */
    public function create($object, array &$data)
    {
        $this->query->insert($data)->into($object)->execute();
    }
    
    /**
     *
     * @param string $object
     * @param array $data
     * @param int $id 
     * 
     */
    public function update($object, array &$data, $id)
    {
       $query = $this->query;
       $query->update($object);
       foreach ($data as $key => $value) {
           $query->set($key . ' = ?', $value);
       }
       $query->where($this->pk . ' = ?', $id)->execute();
    }
    
    /**
     *
     * @param string $object
     * @param int $id 
     * 
     */
    public function delete($object, $id)
    {
        $this->query->delete()->from($object)->where($this->pk . ' = ?', $id)->execute();
    }
    /**
     *
     * @return int  retourne l'id du dernier enregistrement 
     */
    public function lastInsertId()
    {
        return $this->query->lastInsertId();
    }
    
    /**
     *
     * @param string $object
     * @param string $countTable
     * @param array $options 
     */
    public function count($object, $countTable = null, array $options = null)
    {
        $count = new TableCount($object, $countTable, $options);
        return $count->getCount();
    }
    
    /**
     * 
     */
    public function beginTransaction()
    {
       $this->query->db->beginTransaction();
    }
    
    /**
     * 
     */
    public function commit()
    {
       $this->query->db->commit();
    }
    
    /**
     * 
     */
    public function rollBack()
    {
       $this->query->db->rollBack();
    }
}
?>
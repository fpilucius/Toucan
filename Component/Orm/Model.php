<?php
namespace Toucan\Component\Orm;

use Toucan\Component\Orm\Table;
use Toucan\Component\Orm\Relation;

abstract class Model {
    
    protected static $tableName;
    protected static $table;
    public $id = null;
    protected $isNew = null;
    protected $columns = array();
    protected $data = array();

    public function __construct($id = null)
    {
        if($id !=null && is_numeric($id)) {
            $this->isNew = false;
            $this->id = $id;
        } else {
            $this->isNew = true;
        }
        $this->setTableDefinition();
    }
    
    abstract protected function setTableDefinition();
    
    protected function hasColumn($columnName)
    {
    	$this->columns[] = $columnName;
    }
    
    protected function columnExists($name)
    {
        return in_array($name, $this->columns);
    }
    
    public function __set($name, $value)
    {
        if (!isset($this->data[$name]) && $this->columnExists($name)) {
            $this->data[$name] = $value;
        }
    }
    
    public function getId()
    {
        return $this->id;
    }
    
    public static function getTableName()
    {
        $object = preg_replace('/([a-zA-Z]+[\\\\])/i', '', get_called_class());
        $object = strtolower(preg_replace('/([A-Z]+)([A-Z])/','\1_\2', preg_replace('/([a-z\d])([A-Z])/','\1_\2', strval($object))));
        return static::$tableName = $object;
    }
    
    protected  static function getTable()
    {
        return static::$table = new Table();
    }

    public function save() 
    {
        if($this->isNew) {
            static::getTable()->create(static::getTableName(), $this->data);
            $this->id = $this->lastInsertId();
        } else {
            static::getTable()->update(static::getTableName(), $this->data, $this->getId());
        }
    }
    /**
     *
     * @param array $data
     * @return object $model get_called_class() 
     */
    public static function create(array $data)
    {
        $model = new static();
        foreach($data as $key => $value) {
            $model->__set($key, $value);
        }
        $model->save();
        return $model;
    }

    /**
     *
     * @return int  retourne l'id du dernier enregistrement 
     */
    protected function lastInsertId()
    {
        return static::getTable()->lastInsertId();
    }
    
    public function delete() 
    {
        if(!$this->isNew) {
            static::getTable()->delete(static::getTableName(), $this->getId());
        }
    }
    /**
     *
     * @param mixed $options array(fields,conditions) ou numeric(1) 
     * @return array  une ligne de donnée
     */
    
    public static function findOne($options = null)
    {
        $opts = array();
        if(is_numeric($options)) {
            $opts['conditions'] = array('id = ?' => $options);
        } else {
            $opts = $options;
        }
        $opts['limit'] = 1;
        $result = static::find($opts);
        return $result[0];
    }
    
    public static function find(array $options = null)
    {
        return static::getTable()->read(static::getTableName(), $options);
    }
    
    public static function count(array $options = null)
    {
        return static::getTable()->count(static::getTableName(), $options);
    }
    
    public static function transaction($callback)
    {
        if (!(static::getTable() instanceof TransactionInterface)) {
            call_user_func($callback);
            return;
        }
        
        try {
            static::getTable()->beginTransaction();
            call_user_func($callback);
            static::getTable()->commit();
        } 
        catch (\Exception $ex) {
            static::getTable()->rollBack();
            throw $ex;
        }
    }
    
    public static function __callStatic($method, $args)
    {
        if (preg_match('/^(find|findOne|count)By(\w+)$/', $method, $matches)) {
            $criteriaKeys = explode('And', $matches[2]);
            $criteriaKeys = array_map('strtolower', $criteriaKeys);
            $keys = array();  
            foreach ($criteriaKeys as $val) {
                $keys[] = $val . ' = ?';
            }
            $criteriaValues = array_slice($args, 0, count($keys));
            $criteria['conditions'] = array_combine($keys, $criteriaValues);
            $method = $matches[1];
            return static::$method($criteria);
        } else {
            throw new \Exception('la méthode ' . $method . ' n\'existe pas.');
        }
    }   
}
?>
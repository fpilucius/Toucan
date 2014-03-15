<?php

namespace Toucan\Component\Orm;

use Toucan\Component\Orm\Orm;

class Query
{
    public $db;
    protected $sql = '';
    protected $tableName;
    protected $defaultFields = '*';
    protected $setFields;
    protected $where = array();
    protected $dataInsert = array();
    protected $preparDataInsert = array();
    protected $bindParams = array();
    protected $set = array();
    protected $limit = '';
    protected $offset = '';
    protected $groupBy = null;
    protected $having = null;
    protected $between;
    protected $order = null;
    protected $distinct = '';
    protected $join = array();
    protected $type = self::SELECT;
    const INSERTING = 0;
    const DELETING = 1;
    const UPDATING = 2;
    const SELECT = 3;

    public function __construct()
    {
        $this->db = Orm::connection();
    }

    protected function getFetchMode()
    {
        return Orm::$mode;
    }

    public function from($table)
    {
        if (is_array($table)) {
            $table = array_map('strtolower', $table);
            $this->tableName = implode(', ', $table);
        } else {
            $this->tableName = strtolower($table);
        }
        return $this;
    }

    public function into($table)
    {
        $this->tableName = strtolower($table);
        return $this;
    }

    public function select($fields = '')
    {
        $this->setFields = $fields;
        return $this;
    }

    protected function getSelectField()
    {
        if (empty($this->setFields)) {
            return $this->defaultFields;
        } else {
            return $this->setFields;
        }
    }

    protected function getTableName()
    {
        return $this->tableName;
    }

    public function where($condition, $bindParam = null)
    {
        $this->where[] = $condition;
        if ($bindParam != null) {
            if (is_array($bindParam)) {
                foreach ($bindParam as $param) {
                    $this->bindParams[] = $param;
                }
            } else {
                $this->bindParams[] = $bindParam;
            }
        }
        return $this;
    }
    
    public function andWhere($condition, $bindParam = null) {
        $this->where[] = 'AND ' . $condition;
        if ($bindParam != null) {
            if(is_array($bindParam)) {
                foreach($bindParam as $param) {
                    $this->bindParams[] = $param;
                }
            } else {
                $this->bindParams[] = $bindParam;
            }
        }
        return $this;
    }
    
    public function orWhere($condition, $bindParam = null) {
        $this->where[] = 'OR ' . $condition;
        if ($bindParam != null) {
            if(is_array($bindParam)) {
                foreach($bindParam as $param) {
                    $this->bindParams[] = $param;
                }
            } else {
                $this->bindParams[] = $bindParam;
            }
        }
        return $this;
    }
    
    public function whereIn($field, array $bindParam = null) {
        $bind = count($bindParam);
        $i=1;
        $ret = array();
        while($i <= $bind ) {
            $ret[] = '?';
            $i++;
        }
        $this->where[] = $field . ' IN (' . implode(', ', $ret) . ')' ;
        if(is_array($bindParam)) {
            foreach($bindParam as $param) {
                $this->bindParams[] = $param;
            }
        }

        return $this;
    }

    protected function getWhere()
    {

        $_where = $this->where;
        $where = implode(' ', $_where);
        return $where;
    }

    public function set($condition, $bindParam = null)
    {
        $this->set[] = $condition;
        if ($bindParam != null) {
            $this->bindParams[] = $bindParam;
        }
        return $this;
    }

    protected function getSet()
    {
        $_set = $this->set;
        $set = implode(', ', $_set);
        return $set;
    }

    protected function getBindParams()
    {
        return $this->bindParams;
    }

    public function insert($data)
    {
        $this->type = self::INSERTING;
        $this->dataInsert = array_keys($data);
        foreach ($data as $k => $v) {
            $this->bindParams[] = $v;
            $this->preparDataInsert[] = '?';
        }
        return $this;
    }
    
    public function lastInsertId()
    {
        return $this->db->lastInsertId();
    }

    public function update($modelName)
    {
        $this->from($modelName);
        $this->type = self::UPDATING;
        return $this;
    }

    public function delete()
    {
        $this->type = self::DELETING;
        return $this;
    }
    
    protected function getSelect() {
        if (empty($this->setFields)) {
            return $this->defaultFields;
        } else {
            return $this->setFields;
        }
    }

    public function limit($limitCount, $iOffset = 0)
    {
        $this->limit = $limitCount;
        $this->offset = $iOffset;
        return $this;
    }

    protected function getLimit()
    {
        return $this->db->limit($this->limit, $this->offset);
    }

    public function groupBy()
    {
        $args = func_get_args();
        $this->groupBy = implode(",", $args);
        return $this;
    }

    protected function getGroupBy()
    {
        return $this->groupBy;
    }

    public function having($cond, $bindParam = null)
    {
        $this->having = $cond;
        if ($bindParam != null) {
            $this->bindParams[] = $bindParam;
        }
        return $this;
    }

    protected function getHaving()
    {
        return $this->having;
    }

    public function between($Between1, $between2)
    {
        if (is_array($Between)) {
            $this->between = "'" . $Between1 . "' AND '" . $Between2 . "'";
        } else {
            $this->between = '';
        }
        return $this;
    }

    protected function getBetween()
    {
        return $this->between;
    }
    
    public function order($orderBy) {
        
        if (preg_match('/(ASC|DESC)/i', $orderBy)) {
            $this->order = ' ORDER BY ' . $orderBy;
        }else{
            $this->order = ' ORDER BY ' . $orderBy. ' ASC';
        }
        return $this;
    }

    protected function getOrder() {
        return $this->order;
    }
    
    public function leftJoin($classModel, $on = null) {
        $table = strtolower($classModel);
        $this->join[] = ' LEFT JOIN ' . $table . ' ON ' . $on;
        return $this;
    }
    
    public function innerJoin($classModel, $on = null) {
        $table = strtolower($classModel);
        $this->join[] = ' INNER JOIN ' . $table . ' ON ' . $on;
        return $this;
    }

    public function getJoin() {
        $join = implode(' ', $this->join);
        return $join;
    }

    public function getSql()
    {
        switch ($this->type) {
            case 0:
                $this->sql = "INSERT INTO "
                        . $this->getTableName()
                        . ' (' . implode(',', $this->dataInsert) . ') '
                        . ' VALUES (' . implode(', ', $this->preparDataInsert) . ') ';
                break;
            case 1:
                $where = $this->getWhere();
                $this->sql = 'DELETE FROM ' . $this->getTableName() . '
             ' . (!empty($where) ? (' WHERE ' . $where . '') : '');
                break;
            case 2:
                $where = $this->getWhere();
                $_set = $this->getSet();
                $this->sql = 'UPDATE ' . $this->getTableName() . '
                         ' . (!empty($_set) ? (' SET ' . $_set . '') : '') . '
                         ' . (!empty($where) ? (' WHERE ' . $where . '') : '');
                break;
            case 3:
                //TODO between a tester
                $iLimit = $this->getLimit();
                $where = $this->getWhere();
                $groupby = $this->getGroupBy();
                $having = $this->getHaving();
                $between = $this->getBetween();
                $order = $this->getOrder();

                $this->sql = 'SELECT ' . $this->getSelect() .
                        ' FROM ' . $this->getTableName() . $this->getJoin() . '
             ' . (!empty($where) ? ('WHERE ' . $where . '') : '') . '
             ' . (!empty($groupby) ? (' GROUP BY ' . $groupby . '') : '') . '
             ' . (!empty($having) ? (' HAVING ' . $having . '') : '') . '
             ' . (!empty($between) ? (' BETWEEN ' . $between . '') : '') . '
             ' . ($order != null ? ($order) : '') . '
             ' . $iLimit;
                break;
        }
        return $this->sql;
    }

    public function execute()
    {
        $stmt = $this->db->prepare($this->getSql());
        if (count($this->getBindParams()) > 0) {
            $stmt->execute($this->getBindParams());
        } else {
            $stmt->execute();
        }
        $result = '';
        if ($this->type == self::SELECT) {
            $result = $stmt->fetchAll($this->getFetchMode());
        }
        $stmt = null;
        return $result;
    }

    public function __destruct()
    {
        $this->db = null;
    }
}
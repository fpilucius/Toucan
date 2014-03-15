<?php

namespace Toucan\Component\Orm\Table;

use Toucan\Component\Orm\Query;

class TableCount
{

    protected $table;
    protected $relationTable = null;
    protected $options = array();
    protected $query;

    /**
     *
     * @param string $objectName  
     * @param array $options how_many,id,select,having,order,limit=10,offset=0  
     */
    public function __construct($objectName, array $options = null)
    {
        $this->table = $objectName;
        if(isset($options['how_many'])) {
        $this->relationTable = strtolower(preg_replace('/([A-Z]+)([A-Z])/','\1_\2', preg_replace('/([a-z\d])([A-Z])/','\1_\2', strval($options['how_many']))));
        }
        $this->options = $options;
        $this->query = new Query();
    }

    public function getCount()
    {
        if ($this->relationTable != null) {
            if (isset($this->options['select'])) {
                $this->query->select($this->options['select'] . ',COUNT(' . $this->relationTable . '.id) AS count_' . $this->relationTable);
            } else {
                $this->query->select($this->table . '.*,COUNT(' . $this->relationTable . '.id) AS count_' . $this->relationTable);
            }
            if (isset($this->options['id'])) {
                $this->query->from($this->table)
                        ->leftJoin($this->relationTable, $this->table . '.id = ' . $this->relationTable . '.' . $this->table . '_id')
                        ->where($this->table . '.id = ?', $this->options['id'])
                        ->groupBy($this->relationTable . '.' . $this->table . '_id');
                if (isset($this->options['having'])) {
                    $this->query->having($this->options['having']);
                }
                $this->query->limit(1);
                $result = $this->query->execute();
                $row = $result[0];
            } else {
                $this->query->from($this->table)
                        ->leftJoin($this->relationTable, $this->table . '.id = ' . $this->relationTable . '.' . $this->table . '_id')
                        ->groupBy($this->relationTable . '.' . $this->table . '_id');
                if (isset($this->options['having'])) {
                    $this->query->having($this->options['having']);
                }
                if (isset($this->options['order'])) {
                    $order = $this->options['order'];
                } else {
                    $order = $this->table . '.id ASC';
                }
                $this->query->order($order);
                if (isset($this->options['limit'])) {
                    $limit = $this->options['limit'];
                } else {
                    $limit = 10;
                }
                if (isset($this->options['offset'])) {
                    $offset = $this->options['offset'];
                } else {
                    $offset = 0;
                }
                $this->query->limit($limit, $offset);
                $row = $this->query->execute();
            }
        } else {
            //TODO implementer la clause where et mettre en methode magique countByName
            $ro = $this->query->select('COUNT(*) as count')
                    ->from($this->table)
                    ->execute();
            $row = $ro[0];
        }
        //var_dump($this->query->getSql());
        return $row;
    }

}

?>
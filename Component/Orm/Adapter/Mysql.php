<?php
namespace Toucan\Component\Orm\Adapter;

use Toucan\Component\Orm\Convention\AdapterInterface;
use Toucan\Component\Registre\Registry;

class Mysql extends \PDO implements AdapterInterface
{
    protected $container;

    public function __construct()
    {
        $this->container = Registry::get('container');
        parent::__construct($this->dsn(), 
                            $this->get('config')->get('db_user'), 
                            $this->get('config')->get('db_password'),
                            $this->get('config')->get('driver_options')
        );
    }

    public function dsn()
    {
        return 'mysql:host=' . $this->get('config')->get('db_host')
        . ';dbname=' . $this->get('config')->get('db_name');
    }

    public function limit($count, $offset)
    {
        $limit = '';
        if ($count > 0) {
            $limit = ' LIMIT ' . $count;
            if ($offset > 0) {
                $limit .= ' OFFSET ' . $offset;
            }
        }
        return $limit;
    }
    
    public function get($key)
    {
        return $this->container->getService($key);
    }

}
?>

<?php
namespace Toucan\Core\Http;

use Toucan\Component\Session\Session;

class Request
{
    public $request = array();
    public $query;
    public $method;
    public $files = array();
    public $cookie = array();
    public $server = array();
    public $request_uri;
    public $remote_addr;
    public $http_host;
    public $args;

    public function __construct()
    {
        $this->request = array_merge($_GET, $_POST);
        $this->method = $_SERVER['REQUEST_METHOD'];
        $this->files = $_FILES;
        $this->cookie = $_COOKIE;
        $this->remote_addr = $_SERVER['REMOTE_ADDR'];
        $this->request_uri = $_SERVER['REQUEST_URI'];
        $this->http_host = (isset($_SERVER['HTTP_HOST'])) ? $_SERVER['HTTP_HOST'] : '';
        $this->server = $_SERVER;
    }
    
    public function get($key, $default = null)
    {
        return (isset($this->request[$key])) ? $this->request[$key] : $default;
    }
    
    public function isPost()
    {
       if($this->method == 'POST') {
           return true;
       }
    }
    
    public function isGet()
    {
       if($this->method == 'GET') {
           return true;
       }
    }
    
    public function getSession(array $options = array())
    {
        return new Session($options);
    }
}
?>

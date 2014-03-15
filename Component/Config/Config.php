<?php
namespace Toucan\Component\Config;

class Config {

    private $_config = array();   

    public function  __construct($file)
    {
        $this->_config = $file;
    }

    public function get($clef)
    {
        if(isset($this->_config[$clef])) {
            return $this->_config[$clef];
        }
        return false;
    }
}
?>

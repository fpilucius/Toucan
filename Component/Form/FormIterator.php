<?php
namespace Toucan\Component\Form;

class FormIterator implements \Iterator
{

    private $_var = array();

    public function __construct($array)
    {
        if (is_array($array)) {
            $this->_var = $array;
        }
    }

    public function rewind()
    {
        reset($this->_var);
    }

    public function current()
    {
        $var = current($this->_var);
        return $var;
    }

    public function key()
    {
        $var = key($this->_var);
        return $var;
    }

    public function next()
    {
        $var = next($this->_var);
        return $var;
    }

    public function valid()
    {
        $var = $this->current() !== false;
        return $var;
    }

}
?>
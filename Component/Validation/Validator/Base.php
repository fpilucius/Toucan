<?php
namespace Toucan\Component\Validation\Validator;

 use Toucan\Component\Validation\Validator\ValidatorInterface;

abstract class Base implements ValidatorInterface
{
    //protected $container;
    
    protected $options = array();
    
    protected $messages = array();
    
    protected $errors = array();
    
    public function __construct(array $args = null) 
    {
        $this->options = $args;
    }
   
   protected function setMessage($key,$msg)
    {
        $this->messages[] = $msg;
        $this->errors[] = $key;
    }

    public function getMessages()
    {
        return $this->messages;
    }
    
     public function getErrors()
    {
        return $this->errors;
    }
   
    public function getOption($name)
    {
        return $this->options[$name];
    }
    
    protected function hasOption($option)
    {
        return isset($this->options[$option]);
    }
}
?>
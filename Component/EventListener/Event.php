<?php

namespace Toucan\Component\EventListener;

class Event
{

    private $name;
    private $subject;
    private $data;
    private $propagationStopped = false;

    public function __construct($name, $subject = null, $data = null)
    {
        $this->name = $name;
        $this->subject = $subject;
        $this->data = $data;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getSubject()
    {
        return $this->subject;
    }

    public function getdata()
    {
        return $this->data;
    }

    public function isPropagationStopped()
    {
        return $this->propagationStopped;
    }

    public function stopPropagation()
    {
        $this->propagationStopped = true;
    }

}

?>
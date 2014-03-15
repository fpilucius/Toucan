<?php

namespace Toucan\Component\Form\Elements;

use Toucan\Component\Form\Elements\Element;

class Hidden extends Element
{
    protected $validAttribs = array('value',
        'alt',
        'disabled');
    protected $attribs = null;

    public function setAttrib($name, $value)
    {
        if (in_array($name, $this->validAttribs)) {
            $this->attribs[$name] = $value;
        } else {
            throw new \Exception('Invalid attribut: ' . $name);
        }
        return $this;
    }
    
    public function render()
    {
        $render = '';
        $render .= '<input type="hidden" name="' . $this->getName() . '" ' . $this->getAttribs($this->attribs) . '/>';

        return $render;
    }

}

?>

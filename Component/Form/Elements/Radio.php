<?php

namespace Toucan\Component\Form\Elements;

use Toucan\Component\Form\Elements\Element;

class Radio extends Element
{

    protected $validAttribs = array('value',
        'alt',
        'id',
        'class',
        'tabindex',
        'accesskey',
        'disabled',
        'checked',
        'title');
    
    protected $tag = array();
    protected $block;
    protected $attribs = null;

    public function addTag($label = '', array $options = null)
    {
        $labelName = $label;
        if($options != null) {
        foreach ($options as $key => $value) {
            if (in_array($key, $this->validAttribs)) {
                $this->attribs[$key] = $value;
            } else {
                throw new \Exception('Invalid attribut: ' . $key);
            }
        }
        }
        $this->tag[] = '<input type="radio" name="' . $this->getName() . '" ' . $this->getAttribs($this->attribs) . '/> '. $labelName . '  ' . $this->block;
    }

    public function setBlock()
    {
        $this->block = '<br/>';
    }

    public function render()
    {
        $render = '';
        if ($this->label != null) {
            $render .= $this->getLabel() . "\n";
        }
        $tag_debut = '';
        $tag_end = '';
        if (isset($this->decorator['element'])) {
            $class = '';
            if (isset($this->decorator['element']['class_tag'])) {
                $class = ' class="' . $this->decorator['element']['class_tag'] . '"';
            }
            $tag_debut = '<' . $this->decorator['element']['html_tag'] . $class . '>';
            $tag_end = '</' . $this->decorator['element']['html_tag'] . '>';
        }
        $render .= $tag_debut;
        foreach($this->tag as $tag) {
            $render .= $tag;
        }
        $render .= $tag_end;
        $render .= $this->getMsgError();
        return $render;
    }

}

?>

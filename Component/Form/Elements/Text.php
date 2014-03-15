<?php
namespace Toucan\Component\Form\Elements;

use Toucan\Component\Form\Elements\Element;

class Text extends Element
{
    protected $validAttribs = array('value',
        'size',
        'readonly',
        'disabled',
        'maxlength',
        'alt',
        'title',
        'tabindex',
        'accesskey',
        'width',
        'class',
        'style',
        'id'
    );
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
        $render .= $tag_debut . '<input type="text" name="' . $this->getName() . '" ' . $this->getAttribs($this->attribs) . '/>' . $tag_end;
        $render .= $this->getMsgError();

        return $render;
    }

}
?>
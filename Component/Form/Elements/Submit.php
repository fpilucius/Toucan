<?php
namespace Toucan\Component\Form\Elements;

use Toucan\Component\Form\Elements\Element;

class Submit extends Element
{
    protected $validAttribs = array('value',
                                 'alt',
                                 'style',
                                 'title',
                                 'class',
                                 'alt',
                                 'tabindex',
                                 'accesskey',
                                 'disabled'
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
        if(!isset($this->options['value'])) {
            $this->setAttrib('value', 'Envoyer');
        }
        $render = '';
        $tag_debut = '';
        $tag_end = '';
        if (isset($this->decorator['submit'])) {
            $class = '';
            if (isset($this->decorator['submit']['class_tag'])) {
                $class = ' class="' . $this->decorator['submit']['class_tag'] . '"';
            }
            $tag_debut = '<' . $this->decorator['submit']['html_tag'] . $class . '>';
            $tag_end = '</' . $this->decorator['submit']['html_tag'] . '>';
        }
        $render .= $tag_debut . '<input type="submit" name="' . $this->getName() . '" ' . $this->getAttribs($this->attribs) . '/>' . $tag_end;

        return $render;
    }
}
?>

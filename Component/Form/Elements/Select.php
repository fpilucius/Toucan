<?php

namespace Toucan\Component\Form\Elements;

use Toucan\Component\Form\Elements\Element;

class Select extends Element
{
    protected $validAttribs = array('size',
        'multiple',
        'disabled',
        'title',
        'class',
        'style',
        'id'
    );
    protected $attribs = null;
    protected $options = array();
    protected $optgroups = array();
    protected $selected = null;

    public function setAttrib($name, $value)
    {
        if (in_array($name, $this->validAttribs)) {
            $this->attribs[$name] = $value;
        } else {
            throw new \Exception('Invalid attribut: ' . $name);
        }
        return $this;
    }

    public function addOptGroup($group, array $options)
    {
        $this->optgroups[$group] = $options;
    }

    public function addOption($label, $value)
    {
        $this->options[$label] = $value;
    }

    public function addOptions(array $options)
    {
        $this->options = $options;
    }

    public function selected($label)
    {
        $this->selected = $label;
    }

    public function render()
    {
        $render = '';
        if ($this->label != null) {
            $render .= $this->getLabel() . "\n";
        }
        if (!isset($this->attribs['size'])) {
            $this->attribs['size'] = 1;
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
        $render .= '<select name="' . $this->getName() . '" ' . $this->getAttribs($this->attribs) . ">\n";
        if (count($this->optgroups) > 0) {
            foreach ($this->optgroups as $keyname => $value) {
                $render .= '<optgroup label="' . $keyname . "\">\n";
                foreach ($value as $key => $val) {
                    if ($this->selected != null && $this->selected == $key) {
                        $render .= '<option value="' . $val . '" selected="selected">' . $key . "</option>\n";
                    } else {
                        $render .= '<option value="' . $val . '">' . $key . "</option>\n";
                    }
                }
                $render .= "</optgroup>\n";
            }
        } else {
            foreach ($this->options as $key => $value) {
                if ($this->selected != null && $this->selected == $key) {
                    $render .= '<option value="' . $value . '" selected="selected"' . $key . "</option>\n";
                } else {
                    $render .= '<option value="' . $value . '">' . $key . "</option>\n";
                }
            }
        }
        $render .= "</select>\n";
        $render .= $tag_end;
        $render .= $this->getMsgError();

        return $render;
    }
}
?>
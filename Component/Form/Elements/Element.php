<?php
namespace Toucan\Component\Form\Elements;

use Toucan\Component\Validation\Validator\ValidatorInterface;
//TODO ajouter message helper methode addHelpMsg();
abstract class Element implements ValidatorInterface
{
    protected $messages = array();
    protected $label = null;
    protected $optionsValidLabel = array('for', 'id', 'class', 'style');
    protected $otionsLabel = array();
    protected $validators = array();
    protected $filter = array();
    protected $decorator = array();
    protected $name;
    public $value;

    public function __construct($name = null)
    {
        if(get_called_class() == 'Chexbox') {
            $this->name == array();
            $this->setName($name);
        } else {
            $this->setName($name);
            if (null === $this->getName()) {
                throw new \Exception('l\'élement doit avoir un nom');
            }
        }
    }

    protected function getAttribs($attribs)
    {
        $html = '';
        if ($attribs !== null) {
            foreach ($attribs as $keys => $vals) {
                if ($keys == 'readonly' or $keys == 'disabled' or $keys == 'multiple') {
                    $html .= " " . $keys . "";
                } else {
                    $html .= ' ' . $keys . '="' . $vals . '"';
                }
            }
        }
        return $html;
    }
    
    public function isRequired($msg = null){
        $options = null;
        if($msg != null && is_string($msg)) {
            $options['msg'] = $msg;
        }
        $required = array('validator' => 'Required', 'options' => $options);
        array_unshift($this->validators, $required);
        return $this;
    }

    public function addValidator($validator, array $options = null)
    {
        if (is_string($validator)) {
            $name = $validator;
            $validator = array(
                'validator' => $validator,
                'options' => $options
            );
        }
        $this->validators[$name] = $validator;
        return $this;
    }

    protected function loadValidator(array $validator)
    {
        $valid = $validator['validator'];
        $namespace = "Toucan\\Component\\Validation\\Validator\\" . $valid;
        if ($validator['options'] === null) {
            $instance = new $namespace();
        } else {
            $instance = new $namespace($validator['options']);
        }
        return $instance;
    }

    public function getValidators()
    {
        $validators = array();
        foreach ($this->validators as $key => $value) {
            if ($value instanceof ValidateInterface) {
                $validators[$key] = $value;
                continue;
            }
            $validator = $this->loadValidator($value);
            $validators[get_class($validator)] = $validator;
        }
        return $validators;
    }

    public function isValid($value)
    {
        //filter chaine
        $this->value = $value;
        if ($this->hasFilters()) {
            foreach ($this->filter as $filtre) {
                $this->value = call_user_func($filtre, $this->value);
            }
        }
        //validation chaine
        $this->messages = array();
        $result = true;
        foreach ($this->getValidators() as $key => $validator) {
            if (is_array($this->value)) {
                $messages = array();
                 foreach ($this->value as $val) {
                    if (!$validator->isValid($val)) {
                        $result = false;
                        $messages = array_merge($messages, $validator->getMessages());
                    }
                }
                if ($result) {
                    continue;
                }
            } elseif (!$validator->isValid($this->value)) {
                $result = false;
                $messages = $validator->getMessages();
                $this->messages = array_merge($this->messages, $messages);
            }
        }
        return $result;
    }

    /**
     * Paramétrage des décorateurs pour la vue du formulaire
     * label, element, submit, error
     * @param array $decorators 
     */
    public function addDecorators(array $decorators)
    {
        $this->decorator = $decorators;
    }

    public function addFilters()
    {
        $this->filter = func_get_args();
        return $this;
    }

    public function hasFilters()
    {
        $filter = count($this->filter);
        if ($filter > 0) {
            return true;
        }
    }

    public function setLabel($label, array $options = null)
    {
        $this->label = $label;
        $this->otionsLabel = $options;
        return $this;
    }

    public function getLabel()
    {
        $attrib = '';
        $tag_debut = '';
        $tag_end = '';
        if (isset($this->decorator['label'])) {
            $class = '';
            if (isset($this->decorator['label']['class_tag'])) {
                $class = ' class="' . $this->decorator['label']['class_tag'] . '"';
            }
            $tag_debut = '<' . $this->decorator['label']['html_tag'] . $class . '>';
            $tag_end = '</' . $this->decorator['label']['html_tag'] . '>';
        }
        if ($this->otionsLabel !== null) {
            foreach ($this->otionsLabel as $key => $value) {
                if (in_array($key, $this->optionsValidLabel)) {
                    $attrib .= ' ' . $key . '="' . $value . '"';
                }
            }
        }
        return $tag_debut . '<label' . $attrib . '>' . $this->label . '</label>' . $tag_end;
    }

    public function setName($nameElement)
    {
        $this->name = strtolower($nameElement);
        return $this;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getMsgError()
    {
        $render = '';
        if (isset($this->messages[0])) {
            $tag_debut = '';
            $tag_end = '';
            if (isset($this->decorator['error'])) {
                $class = '';
                if (isset($this->decorator['error']['class_tag'])) {
                    $class = ' class="' . $this->decorator['error']['class_tag'] . '"';
                }
                $tag_debut = '<' . $this->decorator['error']['html_tag'] . $class . '>';
                $tag_end = '</' . $this->decorator['error']['html_tag'] . '>';
            }
            $render .= $tag_debut . $this->messages[0] . $tag_end;
        }
        return $render;
    }

}
?>
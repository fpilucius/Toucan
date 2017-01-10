<?php

namespace Toucan\Component\Form;

use Toucan\Component\Form\FormIterator;
use Toucan\Component\Form\Elements\Element;
use Toucan\Component\Registre\Registry;

class Form implements \Countable, \IteratorAggregate {

    /**
     * Form attributs
     * @var array attributs pour la balise form (name, id, method, onsubmit, onreset, action, class).
     */
    protected $attribs = array();

    /**
     *
     * @var array attribut valide pour la balise Form
     */
    protected $validAttribs = array('name', 'id', 'method', 'onsubmit', 'onreset', 'action', 'class', 'enctype', 'accept-charset');

    /**
     *
     * @var array
     */
    protected $methods = array('get', 'post');
    protected $elements = array();

    const ENCTYPE_URLENCODED = 'application/x-www-form-urlencoded';
    const ENCTYPE_MULTIPART = 'multipart/form-data';

    function __construct() {
        // Method POST par defaut.
        $this->setMethod('post');
        $this->configure();
    }

    protected function configure() {

    }

    public function setAction($action) {
        return $this->setAttrib('action', (string) $action);
    }

    public function setMethod($method) {
        $method = strtolower($method);
        if (!in_array($method, $this->methods)) {
            throw new \Exception('invalid form method: ' . $method);
        } else {
            return $this->setAttrib('method', $method);
        }
    }

    public function create() {
        return "<form " . $this->getAttribs() . ">\n";
    }

    public function body() {
        $render = '';
        if ($this->count() > 0) {
            foreach ($this->getIterator() as $name => $elem) {
                $render .= $this->row($name)->render() . "\n";
            }
        }
        return $render;
    }

    public function end($submit = null, $submitCssClass = '') {
        $sub = '';
        $class = '';
        if ($submitCssClass != '') {
            $class = 'class="' . $submitCssClass . '"';
        }
        if ($submit != null) {
            $sub = '<input type="submit" name="' . strtolower($submit) . '" ' . $class . ' value="' . ucfirst($submit) . '"/>';
        }
        $sub .= "\n</form>\n";
        return $sub;
    }

    /**
     *
     * @param mixed $element
     * @return Form
     */
    public function add($element) {
        if (is_array($element)) {
            foreach ($element as $value) {
                if ($value instanceof Element) {
                    $name = $value->getName();
                }
                $this->elements[$name] = $value;
            }
        } else {
            if ($element instanceof Element) {
                $name = $element->getName();
            }
            $this->elements[$name] = $element;
        }
        return $this;
    }

    public function count() {
        return count($this->elements);
    }

    public function setAttrib($name, $value) {
        if (in_array($name, $this->validAttribs)) {
            $this->attribs[$name] = $value;
        } else {
            throw new \Exception('Invalid attribut: ' . $name);
        }
        return $this;
    }

    public function setEnctypeFile() {
        $this->setAttrib('enctype', self::ENCTYPE_MULTIPART);
        return $this;
    }

    protected function getAttribs() {
        $attribs = '';
        foreach ($this->attribs as $key => $value) {
            $attribs .= $key . '="' . $value . '" ';
        }
        return $attribs;
    }

    public function getIterator() {
        return new FormIterator($this->elements);
    }

    public function row($name) {
        if (array_key_exists($name, $this->elements)) {
            return $this->elements[$name];
        }
    }

    public function render() {
        $render = '';
        $render .= $this->create();
        $render .= $this->body();
        $render .= $this->end();
        return $render;
    }

    public function isValid($data = '') {
        if ($data == '') {
            $data = $_POST;
        }
        $valid = true;
        foreach ($this->getIterator() as $name => $element) {
            if (!isset($data[$name])) {
                $valid = $element->isValid(null) && $valid;
            } else {
                $valid = $element->isValid($data[$name]) && $valid;
            }
        }
        return $valid;
    }

    /**
     *
     * @param string $name
     * @return une valeur filtrée et validée
     */
    public function getValue($name) {
        return $this->row($name)->value;
    }

}

?>
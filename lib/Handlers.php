<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Handlers
 *
 * @author ltineo
 */
class Handlers implements Countable, Iterator, ArrayAccess {

    protected $data = array();
    protected $viewFile = '';
    protected $key = null;

    public function assign($data) {
        $this->data = $data;
    }

    public function getData() {
        return $this->data;
    }

    public function hasData($key) {
        return $this->valid($key);
    }

    public function getView() {
        require_once $this->viewFile;
    }

    public function count() {
        return count($this->data);
    }

    public function current() {
        return curret($this->data);
    }

    public function rewind() {
        reset($this->data);
        return $this;
    }

    public function next() {
        next($this->data);
        return $this;
    }

    public function key() {
        return key($this->current());
    }

    public function valid($key = null) {
        if ($key !== null) {
            $this->key = $key;
        }
        return isset($this->data[$this->key]);
    }

    /**
     * Implementation of ArrayAccess::offsetSet()
     *
     * @link http://www.php.net/manual/en/arrayaccess.offsetset.php
     * @param string $offset
     * @param mixed $value
     */
    public function offsetSet($offset, $value) {
        $this->data[$offset] = $value;
    }

    /**
     * Implementation of ArrayAccess::offsetExists()
     *
     * @link http://www.php.net/manual/en/arrayaccess.offsetexists.php
     * @param string $offset
     * @return boolean
     */
    public function offsetExists($offset) {
        return $this->valid($offset);
    }

    /**
     * Implementation of ArrayAccess::offsetUnset()
     *
     * @link http://www.php.net/manual/en/arrayaccess.offsetunset.php
     * @param string $offset
     */
    public function offsetUnset($offset) {
        unset($this->data[$offset]);
    }

    /**
     * Implementation of ArrayAccess::offsetGet()
     *
     * @link http://www.php.net/manual/en/arrayaccess.offsetget.php
     * @param string $offset
     * @return mixed
     */
    public function offsetGet($offset) {
        return $this->valid($offset) ? $this->data[$offset] : null;
    }

    /**
     * Convert object attributes to array
     *
     * @param  array $arrAttributes array of required attributes
     * @return array
     */
    public function __toArray(array $arrAttributes = array()) {
        if (empty($arrAttributes)) {
            return $this->data;
        }

        $arrRes = array();
        foreach ($arrAttributes as $attribute) {
            if (isset($this->valid($attribute))) {
                $arrRes[$attribute] = $this->data[$attribute];
            } else {
                $arrRes[$attribute] = null;
            }
        }
        return $arrRes;
    }

    /**
     * Set required array elements
     *
     * @param   array $arr
     * @param   array $elements
     * @return  array
     */
    protected function _prepareArray(&$arr, array $elements = array()) {
        foreach ($elements as $element) {
            if (!isset($arr[$element])) {
                $arr[$element] = null;
            }
        }
        return $arr;
    }

    /**
     * Convert object attributes to JSON
     *
     * @param  array $arrAttributes array of required attributes
     * @return string
     */
    protected function __toJson(array $arrAttributes = array()) {
        $arrData = $this->toArray($arrAttributes);
        $json = Zend_Json::encode($arrData);
        return $json;
    }

    /**
     *
     * Will use $format as an template and substitute {{key}} for attributes
     *
     * @param string $format
     * @return string
     */
    public function toString($format = '') {
        if (empty($format)) {
            $str = implode(', ', $this->getData());
        } else {
            preg_match_all('/\{\{([a-z0-9_]+)\}\}/is', $format, $matches);
            foreach ($matches[1] as $var) {
                $format = str_replace('{{' . $var . '}}', $this->getData($var), $format);
            }
            $str = $format;
        }
        return $str;
    }

}


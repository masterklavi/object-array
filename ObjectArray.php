<?php

class ObjectArray implements Iterator, ArrayAccess
{
    protected $_keys = [];
    protected $_values = [];

    public function offsetExists($offset)
    {
        if (!is_object($offset))
        {
            return false;
        }

        $hash = spl_object_hash($offset);
        return (isset($this->_values[$hash]));
    }
    
    public function offsetGet($offset)
    {
        if (!is_object($offset))
        {
            return null;
        }

        $hash = spl_object_hash($offset);

        if (!isset($this->_values[$hash]))
        {
            return null;
        }

        return ($this->_values[$hash]);
    }

    public function offsetSet($offset, $value)
    {
        if (!is_object($offset))
        {
            return;
        }

        $hash = spl_object_hash($offset);
        $this->_values[$hash] = $value;
        $this->_keys[$hash] = $offset;
    }

    public function offsetUnset($offset)
    {
        if (!is_object($offset))
        {
            return;
        }

        $hash = spl_object_hash($offset);
        
        if (!isset($this->_values[$hash]))
        {
            return;
        }

        unset($this->_values[$hash]);
        unset($this->_keys[$hash]);
    }

    public function current()
    {
        return (current($this->_values));
    }
    
    public function key()
    {
        $hash = key($this->_values);

        if (is_null($hash))
        {
            return null;
        }

        return ($this->_keys[$hash]);
    }

    public function next()
    {
        return (next($this->_values));
    }

    public function rewind()
    {
        return (reset($this->_values));
    }
    
    public function valid()
    {
        $hash = key($this->_values);

        if (is_null($hash))
        {
            return false;
        }

        return (isset($this->_keys[$hash]));
    }

    
    public function size()
    {
        return (count($this->_values));
    }
}

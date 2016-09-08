<?php namespace ChaoticWave\Twister\Containers;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;

class TwisterContainer implements \ArrayAccess, Arrayable, \Countable, Jsonable
{
    //******************************************************************************
    //* Members
    //******************************************************************************

    /**
     * @var array A hash of loadable keys within the container. Format = [ 'key' => [ 'class' => classname, 'array' => true|false ] ], ...
     */
    protected $_loadable;

    //******************************************************************************
    //* Methods
    //******************************************************************************

    /**
     * Constructor
     *
     * @param array|object $items
     */
    public function __construct($items = [])
    {
        if (!empty($this->_loadable)) {
            foreach ($this->_loadable as $_name => $_info) {
                $this->loadObject($_name, $items, array_get($_info, 'class', \stdClass::class), array_get($_info, 'array', false));
            }
        }

        foreach ($items as $_key => $_value) {
            //  Merge *_str values
            if ('_str' === substr($_key, -4)) {
                $_key = substr($_key, 0, -4);
            }

            $this->{$_key} = $_value;
        }
    }

    /** @inheritdoc */
    public function offsetExists($offset)
    {
        return property_exists($this, $offset);
    }

    /** @inheritdoc */
    public function offsetGet($offset)
    {
        return data_get($this, $offset);
    }

    /** @inheritdoc */
    public function offsetSet($offset, $value)
    {
        data_set($this, $offset, $value);
    }

    /** @inheritdoc */
    public function offsetUnset($offset)
    {
        if ($this->offsetExists($offset)) {
            unset($this->{$offset});
        }
    }

    /** @inheritdoc */
    public function count()
    {
        return sizeof($this->toArray());
    }

    /** @inheritdoc */
    public function toArray()
    {
        return array_except(get_object_vars($this), ['_loadable']);
    }

    /** @inheritdoc */
    public function toJson($options = 0)
    {
        return json_encode($this->toArray(), $options);
    }

    /**
     * Loads sub-objects into their own classes
     *
     * @param string       $name
     * @param array|object $items
     * @param string       $class
     * @param bool         $array True if we're talking about an array of objects or just one
     */
    protected function loadObject($name, &$items, $class, $array = false)
    {
        $_list = null;

        if (null !== ($_objects = data_get($items, $name))) {
            if ($array) {
                foreach ($_objects as $_object) {
                    $_list[] = new $class($_object);
                    unset($_object);
                }
            } else {
                $_list[] = new $class($_objects);
            }

            data_set($items, $name, $_list);
        }
    }
}

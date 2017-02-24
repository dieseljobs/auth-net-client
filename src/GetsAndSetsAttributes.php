<?php

namespace TheLHC\AuthNetClient;

trait GetsAndSetsAttributes
{
    /**
     * Object attributes
     *
     * @var array
     */
    private $attributes = [];

    /**
     * Persisted attributes when retrieved from stored object
     *
     * @var array
     */
    private $original = [];

    /**
     * Construct new instance - sets passed array as attributes
     *
     * @param array $attrs
     * @param boolean $exists
     */
    public function __construct($attrs = [], $exists = false)
    {
        $this->attributes = $attrs;
        if ($exists) $this->original = $attrs;
    }

    /**
     * Get inaccessible attribute, map to attributes prop
     *
     * @param  string $key
     * @return mixed
     */
    public function __get($key)
    {
        if (isset($this->attributes[$key])) {
            return $this->attributes[$key];
        } else {
            return null;
        }
    }

    /**
     * Set inaccessible attribute, set to attributes prop
     *
     * @param string $key
     * @param mixed $value
     */
    public function __set($key, $value)
    {
        $this->attributes[$key] = $value;
    }

    /**
     * Overload cast to string method
     *
     * @return string
     */
    public function __toString()
    {
        return json_encode($this->attributes);
    }

    /**
     * Cast object to array, returns attributes prop
     *
     * @return array
     */
    public function toArray()
    {
        return $this->attributes;
    }

    /**
     * Push val to array attribute
     *
     * @param  string $key
     * @param  mixed $val
     * @return void
     */
    public function pushToArrAttr($key, $val)
    {
        $this->attributes[$key][] = $val;
    }
}

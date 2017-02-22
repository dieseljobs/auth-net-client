<?php

namespace TheLHC\AuthNetClient;

class CreditCard
{
    private $attributes = [];

    public function __construct($attrs = [])
    {
        $this->attributes = $attrs;
    }

    public function __get($key)
    {
        if (isset($this->attributes[$key])) {
            return $this->attributes[$key];
        } else {
            return null;
        }
    }

    public function __set($key, $value)
    {
        $this->attributes[$key] = $value;
    }

    public function __toString()
    {
        return json_encode($this->attributes);
    }

    public function toArray()
    {
        return $this->attributes;
    }

    public function valid()
    {
        return ($this->number && $this->month && $this->year);
    }

}

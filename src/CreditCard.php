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

}

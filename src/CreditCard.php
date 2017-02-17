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
        return $this->values[ $key ];
    }

    public function __set($key, $value)
    {
        $this->attributes[$key] = $value;
    }

}

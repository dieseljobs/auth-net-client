<?php

namespace TheLHC\AuthNetClient;

use IteratorAggregate;
use ArrayIterator;

class Collection implements IteratorAggregate
{
    private $items;
    private $parent;

    public function __construct($items, $parent = null)
    {
        $this->items = $items;
        $this->parent = $parent;
    }

    public function getIterator()
    {
        return new ArrayIterator($this->items);
    }

    public function save($obj)
    {
        // pass parent key to child
        if ($this->parent && method_exists($this->parent, "getKey")) {
            $parentKey = $this->parent->getKey();
            $parentVal = $this->parent->$parentKey;
            if (is_null($obj->$parentKey)) {
                $obj->$parentKey = $parentVal;
            }
        }
        $response = $obj->create();
        return $response;
    }

}

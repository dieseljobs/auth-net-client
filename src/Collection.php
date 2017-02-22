<?php

namespace TheLHC\AuthNetClient;

use IteratorAggregate;
use ArrayIterator;

class Collection implements IteratorAggregate
{
    private $items;
    private $relationKey;
    private $parent;

    public function __construct($items, $relationKey = null, $parent = null)
    {
        $this->items = $items;
        $this->relationKey = $relationKey;
        $this->parent = $parent;
    }

    public function getIterator()
    {
        return new ArrayIterator($this->items);
    }

    public function add($obj)
    {
        $this->autosetParentKey($obj);
        $this->appendItem($obj);
    }

    public function save($obj)
    {
        $this->autosetParentKey($obj);
        $response = $obj->create();
        $this->appendItem($obj);
        return $response;
    }

    private function autosetParentKey(&$obj)
    {
        if (!($this->parent && method_exists($this->parent, "getKey"))) return;
        $parentKey = $this->parent->getKey();
        $parentVal = $this->parent->$parentKey;
        if (is_null($obj->$parentKey) && !empty($parentVal)) {
            $obj->$parentKey = $parentVal;
        }
    }

    private function appendItem(&$obj)
    {
        if (!($this->relationKey && $this->parent)) return;
        $this->parent->pushToArrAttr($this->relationKey, $obj);
    }
}

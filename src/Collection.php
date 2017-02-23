<?php

namespace TheLHC\AuthNetClient;

use IteratorAggregate;
use ArrayIterator;
use Illuminate\Support\Arr;

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

    public function find($key)
    {
        return Arr::first($this->items, function ($index, $item) use ($key) {
            if (!is_integer($index)) {
                return $index->getKey() == $key;
            }
            return $item->getKey() == $key;
        }, null);
    }

    private function autosetParentKey(&$obj)
    {
        if (!($this->parent && method_exists($this->parent, "getKey"))) return;
        $parentKey = $this->parent->getKeyName();
        $parentVal = $this->parent->getKey();
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

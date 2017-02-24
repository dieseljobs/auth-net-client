<?php

namespace TheLHC\AuthNetClient;

use IteratorAggregate;
use ArrayIterator;
use Illuminate\Support\Arr;

class Collection implements IteratorAggregate
{
    /**
     * Collection of objects
     *
     * @var array
     */
    private $items;

    /**
     * Key to relate to parent
     *
     * @var string
     */
    private $relationKey;

    /**
     * Parent object if children of
     *
     * @var mixed
     */
    private $parent;

    /**
     * Create new instance
     *
     * @param array $items
     * @param string $relationKey
     * @param mixed $parent
     */
    public function __construct($items, $relationKey = null, $parent = null)
    {
        $this->items = $items;
        $this->relationKey = $relationKey;
        $this->parent = $parent;
    }

    /**
     * Overload's IteratorAggregate getIterator method
     * Default behavior when class instance is used as array
     *
     * @return array
     */
    public function getIterator()
    {
        return new ArrayIterator($this->items);
    }

    /**
     * Add object to collection
     *
     * @param mixed $obj
     */
    public function add($obj)
    {
        $this->autosetParentKey($obj);
        $this->appendItem($obj);
    }

    /**
     * Save new object and add to collection, then return Response instance
     *
     * @param  mixed $obj
     * @return Response
     */
    public function save($obj)
    {
        $this->autosetParentKey($obj);
        $response = $obj->create();
        $this->appendItem($obj);
        return $response;
    }

    /**
     * Find an object in colllection array by key
     *
     * @param  string $key
     * @return mixed
     */
    public function find($key)
    {
        return Arr::first($this->items, function ($index, $item) use ($key) {
            if (!is_integer($index)) {
                return $index->getKey() == $key;
            }
            return $item->getKey() == $key;
        }, null);
    }

    /**
     * If object is child of a parent object, pass down association key
     *
     * @param mixed $obj
     */
    private function autosetParentKey(&$obj)
    {
        if (!($this->parent && method_exists($this->parent, "getKey"))) return;
        $parentKey = $this->parent->getKeyName();
        $parentVal = $this->parent->getKey();
        if (is_null($obj->$parentKey) && !empty($parentVal)) {
            $obj->$parentKey = $parentVal;
        }
    }

    /**
     * Pushes new item back to parent array property if applicable
     *
     * @param  mixed $obj
     * @return void      
     */
    private function appendItem(&$obj)
    {
        if (!($this->relationKey && $this->parent)) return;
        $this->parent->pushToArrAttr($this->relationKey, $obj);
    }
}

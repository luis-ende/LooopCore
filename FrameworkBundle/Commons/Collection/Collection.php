<?php

namespace LooopCore\FrameworkBundle\Commons\Collection;

/**
 * Our Collection class implementation
 * http://www.blrf.net/blog/198/code/php/iterator-for-collections-in-php/
 */
class Collection implements \IteratorAggregate {

  /**
   * This is our array with data (collection)
   */
  private $data = array();

  /**
   * Get iterator for this collection
   *
   * This method will return <b>CollectionIterator</b> object we wrote before.
   */
  public function getIterator(){
    return new CollectionIterator($this);
  }

  /**
   * Add item to collection
   *
   * This method will add item to collection.
   *
   * If you do now provide the key, key will be next available.
   */
  public function add($item, $key = null){
    if ($key === null){
      // key is null, simply insert new data
      $this->data[] = $item;
    }
    else {
      // key was specified, check if key exists
      if (isset($this->data[$key]))
        throw new ECollectionKeyInUse($key);
      else
        $this->data[$key] = $item;
    }
  }

  /**
   * Get item from collection by key
   *
   */
  public function get($key){
    if (isset($this->data[$key]))
      return $this->data[$key];
    else
      throw new ECollectionKeyInvalid($key);
  }

  /**
   * Remove item from collection
   *
   * This method will remove item from collection.
   */
  public function remove($key){
    // check if key exists
    if (!isset($this->data[$key]))
      throw new ECollectionKeyInvalid($key);
    else
      unset($this->data[$key]);
  }

  /**
   * Get all items in collection
   */
  public function getAll(){
    return $this->data;
  }

  /**
   * Get all the keys in collection
   *
   */
  public function keys(){
    return array_keys($this->data);
  }

  /**
   * Get number of entries in collection
   */
  public function length(){
    return count($this->data);
  }

  /**
   * Clear the collection
   *
   * This method removes all the item from the collection
   */
  public function clear(){
    $this->data = array();
  }

  /**
   * Check if key exists in collection
   */
  public function exists($key){
    return isset($this->data[$key]);
  }
}
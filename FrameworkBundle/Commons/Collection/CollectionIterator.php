<?php

namespace LooopCore\FrameworkBundle\Commons;

class CollectionIterator implements Iterator {
  /**
   * This is our collection class, defined later in article.
   */
  private $Collection = null;
  /**
   * Current index
   */
  private $currentIndex = 0;
  /**
   * Keys in collection
   */
  private $keys = null;

  /**
   * Collection iterator constructor
   *
   */
  public function __construct(Collection $Collection){
    // assign collection
    $this->Collection = $Collection;
    // assign keys from collection
    $this->keys = $Collection->keys();
  }

  /**
   * Implementation of method current
   *
   * This method returns current item in collection based on currentIndex.
   */
  public function current(){
    return $this->Collection->get($this->key());
  }

  /**
   * Get current key
   *
   * This method returns current items' key in collection based on currentIndex.
   */
  public function key(){
    return $this->keys[$this->currentIndex];
  }

  /**
   * Move to next idex
   *
   * This method increases currentIndex by one.
   */
  public function next(){
    ++$this->currentIndex;
  }

  /**
   * Rewind
   *
   * This method resets currentIndex by setting it to 0
   */
  public function rewind(){
    $this->currentIndex = 0;
  }

  /**
   * Check if current index is valid
   *
   * This method checks if current index is valid by checking the keys array.
   */
  public function valid(){
    return isset($this->keys[$this->currentIndex]);
  }
}
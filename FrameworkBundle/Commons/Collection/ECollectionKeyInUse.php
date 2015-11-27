<?php

namespace LooopCore\FrameworkBundle\Commons\Collection;

/**
 * Our ECollectionKeyInUse exception
 *
 * Thrown when you try to insert new item with key that already exists in collection.
 */
class ECollectionKeyInUse extends Exception {

  public function __construct($key){
    parent::__construct('Key ' . $key . ' already exists in collection');
  }
}
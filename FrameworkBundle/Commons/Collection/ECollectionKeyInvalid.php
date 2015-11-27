<?php

namespace LooopCore\FrameworkBundle\Commons\Collection;

/**
 * Our ECollectionKeyInvalid exception
 *
 * Exception that will be thrown when you try to get an item with the key
 * that does not exist in collection.
 */
class ECollectionKeyInvalid extends Exception {

  public function __construct($key){
    parent::__construct('Key ' . $key . ' does not exist in collection');
  }
}
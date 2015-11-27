<?php

namespace LooopCore\FrameworkBundle\Entity;

use ArrayAccess;

/**
 * Das Interface für alle Entities.
 * Idee: 1. Entity-Klassen lassen sich auf den Typ prüfen, z.B. Operator "instanceof"
 *       2. Die Funktionen des Traits können definiert werden.
 */
interface EntityInterface extends ArrayAccess {
    public static function getEntityManager();
    public static function getRepository();
    public function offsetExists($offset);
    public function writeOffsetExists($offset);
    public function offsetSet($offset, $value);
    public function offsetGet($offset);
    public function offsetUnset($offset);
    public function __toString();
    public function getDbIdentifier();
//    public function getId();
    public static function columnToAttribute($string);
    public static function attributeToColumn($string);
    public static function camelCaseToUnderscore($val);
    public static function getClassMetaData();
    public static function getEntityName();
    public static function flushAllObjects();
    public function persist();
    public function saveOnNextFlush($noLog = false, $noDates = false);
    public function save($noLog = false, $noDates = false);
    public function deleteOnNextFlush();
    public function deleteAndFlush($noLog = false);
    public function refresh();
//    public function entityExists();
//    public function _load();
    public function setValues($values, $dbNames = false, $fieldNames = false, $nurDbAttribute = false);
    public function getValues($db_names = false, $dateToString = false, $toFieldName = true);
    public function toDbColumnArray($dateToString = true);
    public function toFieldColumnArray($dateToString = true);
    public function setCreationTimestamp();
    public function setUpdateTimestamp();
    public function logChangeToDb($action = "update");
    public function logDeleteToDb();
    public function logCreationToDb();
    public function getDbTableName();

}
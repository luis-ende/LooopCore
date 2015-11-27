<?php

namespace LooopCore\FrameworkBundle\Entity;

use DateTime;
use Doctrine\DBAL\LockMode;
use Doctrine\Entity;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\ClassMetadata;
use LooopCore\FrameworkBundle\Helper\ModelHelper;
use LLPalt\BackendBundle\Entity\LogDbChange;

//use Doctrine\ORM\Mapping\ClassMetadata;

/**
 * Trait für alle Entitäten.
 * Doku siehe auch EntityInterface.php
 * 
 * Hier können Methoden festgelegt werden,
 * die über jede Entitätsklasse aufgerufen werden kann.
 */
trait EntityTrait {
    /* @see setUserComment() */

    public static $_userComment = null;
    public $__isInitialized__ = true;
    public $_entityExists = true;
    public $_noLog = false;
    public $_noDates = false;

    /**
     * return the doctrine Entity Manager (which does mapping and everything related to the DB)
     * Abhängigkeit von LooopCoreFrameworkBundle
     * @return EntityManager
     */
    public static function getEntityManager() {
        return ModelHelper::getEntityManager();
    }
    
    /**
     * Erzeugt ein Doctrine-Repository (mit Suchfunktionen etc.) für die aktuelle Klasse
     * Abhängigkeit von LooopCoreFrameworkBundle
     * @return EntityRepository Die Repository-Klasse .
     */
    private static function __getRepository() {
        return ModelHelper::getRepository(self::getEntityName());
    }



    /** function needed for Array Access */
    public function offsetExists($offset) {
        $offset = ucfirst(self::columnToAttribute($offset));
        return method_exists($this, "get$offset");
    }

    /** function needed for Array Access */
    public function writeOffsetExists($offset) {
        $offset = ucfirst(self::columnToAttribute($offset));
        return method_exists($this, "set$offset");
    }

    /** function needed for Array Access */
    public function offsetSet($offset, $value) {
        if ($this->writeOffsetExists($offset)) {
            $offset = ucfirst(self::columnToAttribute($offset));
            $this->{"set$offset"}($value);
        }
    }

    /** function needed for Array Access */
    public function offsetGet($offset) {
        if ($this->offsetExists($offset)) {
            $offset = ucfirst(self::columnToAttribute($offset));
            return $this->{"get$offset"}();
        }
    }

    /** function needed for Array Access */
    public function offsetUnset($offset) {
        if ($this->writeOffsetExists($offset)) {
            $offset = ucfirst(self::columnToAttribute($offset));
            $this->{"set$offset"}(null);
        }
    }

    /** toString-Methode zum Vergleichen/Filtern von Objekten */
    public function __toString() {
        return spl_object_hash($this);
    }

    /**
     * Gibt den Wert des Primary Key aus der DB zurück 
     */
    public function getDbIdentifier() {
        $identifier = $this->getEntityManager()->getUnitOfWork()->getEntityIdentifier($this);
        return array_shift($identifier);
    }

//    /**
//     * Falls diese Methode nicht von der Entity überschrieben wird (z.B. weil sie ein Feld "id" hat),
//     * wird hier der Identifier dieses Eintrags unabhängig vom tatsächlichen
//     * DB-Namen zurückgegeben
//     */
//    public function getId() {
//        return $this->getDbIdentifier();
//    }

    /**
     * returns true, wenn es einen DB-Identifier gibt, der nicht NULL / 0 ist
     * (um zu testen, ob dieses Objekt wirklich kein Dummy ist)
     * @return boolean 
     */
    private function hasDbIdentifier() {
        if ($this->getDbIdentifier()) {
            return true;
        }
        return false;
    }

    /**
     * replaces a string of the form under_score_case by the camelCase variant
     * -> testet, ob eine geg. Spalte in der DB existiert. Wenn ja, wird der
     *    entsprechende CamelCaseEintrag zurückgegeben
     * -> ansonsten nach regex
     */
    public static function columnToAttribute($string) {
        // test, ob dieser Wert als DB-Spalte existiert
        return self::getClassMetaData()->getFieldName($string);
//        $columnNames = $this->getClassMetaData()->getColumnNames();
//        if (in_array($string, $columnNames)) {
//        }
//        // ansonsten mache stupide Umwandlung mit RegEx
//        return preg_replace('/_(.?)/e',"strtoupper('$1')", $string);
    }

    /**
     * replaces a string of the form under_score_case by the camelCase variant
     */
    public static function attributeToColumn($string) {
        // test, ob dieser Wert als Attribut existiert
        return self::getClassMetaData()->getColumnName($string);
//        $fieldNames = $this->getClassMetaData()->getFieldNames();
//        if (in_array($string, $fieldNames)) {
//        }
//        // ansonsten mache stupide Umwandlung mit RegEx
//        return strtolower(preg_replace('/([a-z])([A-Z])/', '$1_$2', $string));
    }

    /**
     * wandelt camelCase in underscore_syntax um
     */
    public static function camelCaseToUnderscore($val) {
        return strtolower(preg_replace('/([a-z])([A-Z])/', '$1_$2', $val));
    }

    public static $_classMetaData = array();

    /**
     * returns the metadata of this class from the mapping
     * @return ClassMetadata
     */
    public static function getClassMetaData() {
        if (!isset(self::$_classMetaData[self::getEntityName()])) {
            self::$_classMetaData[self::getEntityName()] = self::getEntityManager()->getClassMetadata(get_called_class());
        }
        return self::$_classMetaData[self::getEntityName()];
    }

    /**
     * Gibt den Namen der aktuellen Klasse zurück
     * @return string
     */
    public static function getEntityName() {
        return get_called_class();
    }

    /**
     * @param type $identifier
     * @param type $lockMode
     * @param type $lockVersion
     * @return Entity
     */
    public static function find($identifier, $lockMode = LockMode::NONE, $lockVersion = null) {
        if (!$identifier) {
            return null;
        }

        return self::getEntityManager()->find(self::getEntityName(), $identifier, $lockMode, $lockVersion);
    }

    /**
     * Schreibe bzw. Lösche alle geänderten und zum Speichern ("persist") markioerten Objekte
     * in die Datenbank
     */
    public static function flushAllObjects() {
        self::getEntityManager()->flush();
        self::getEntityManager()->flush();
    }

    /**
     * markiere dieses Objekt als persistent (wird beim nächsten Durchlauf ("flush") gespeichert) 
     */
    public function persist() {
        self::getEntityManager()->persist($this);
    }

    /**
     * Alias für persist() -> Objekt wird beim nächsten Durchlauf ("flush") gespeichert
     * @param boolean $noLog Logging für diesen Speichervorgang ausschalten
     * @param boolean $noDates Setzen von geaendertAm für diesen Speichervorgang ausschalten
     */
    public function saveOnNextFlush($noLog = false, $noDates = false) {
        $this->_noLog = $noLog;
        $this->_noDates = $noDates;
        $this->persist();
    }

    /**
     * Speichere Objekt *sofort*!
     * -> Anmerkung: Wenn mehrere Objekte gespeichert werden, empfiehlt sich
     * stattdessen saveOnNextFlush
     * @param boolean $noLog Logging für diesen Speichervorgang ausschalten
     * @param boolean $noDates Setzen von geaendertAm für diesen Speichervorgang ausschalten
     * @return boolean true, if the saving was successful
     */
    public function save($noLog = false, $noDates = false) {
        $this->_noLog = $noLog;
        $this->_noDates = $noDates;
        
        if (method_exists(self::__getRepository(), "save")) {
            return self::__getRepository()->save($this);
        } else {
            $this->persist();
            self::getEntityManager()->flush($this);
        }
        
        return true;
    }

    /**
     * Lösche Objekt beim nächsten Durchlauf ("flush") 
     */
    public function deleteOnNextFlush() {
        $em = self::getEntityManager();
        $em->remove($this);
    }

    /**
     * Lösche Objet sofort. 
     * -> Anmerkung: Wenn mehrere Objekte gelöscht werden, empfiehlt sich
     * stattdessen deleteOnNextFlush
     */
    public function deleteAndFlush($noLog = false) {
        $this->_noLog = $noLog;
        $em = self::getEntityManager();
        $em->remove($this);
        $em->flush();
    }

    /**
     * Reload this object from the database
     */
    public function refresh() {
        self::getEntityManager()->refresh($this);
    }

    /**
     * gibt TRUE zurück, wenn die Entität mit dieser ID wirklich existiert.
     * und die ID > 0 ist. (ID=0 wird in Doctrine generell nicht unterstützt)
     * -> Da Doctrine lazy loading verwendet, werden die Objekte erst geladen, wenn ein Attribut
     *    daraus tatsächlich verwendet wird.
     * Unsere DB ist chaotisch und ref. Integrität nicht immer gewährleistet
     * -> Hiermit kann man ohne Exception testen, ob das Objekt wirklich existiert.
     */
    final public function entityExists() {
        // Schon initialisierte Objekte existieren immer
        if ($this->__isInitialized__ && $this->hasDbIdentifier() && $this->_entityExists) {
            return true;
        }

        // ansonsten lade das Objekt aus der DB ()
        try {
            $this->_load();
        } catch (EntityNotFoundException $e) {
            $this->_entityExists = false;
            return false;
        }
        return $this->getId() > 0;
    }

    /**
     * Stellt die Methode aus proxy-klassen auch in den Entities zur Verfügung, 
     * wo sie aber nichts tun muss
     */
    public final function _load() {
        if (method_exists($this, "__load")) {
            return $this->__load();
        }
    }

    /**
     * @param type $values Array with the values to set
     * -> $values kann auch ein Objekt sein, da alle Entitäten auch 
     *    einen Array-Zugriff haben.
     */
    public function setValues($values, $dbNames = false, $fieldNames = false, $nurDbAttribute = false) {
        $classMetaData = self::getClassMetaData();
        $fieldNames = $classMetaData->getFieldNames();
        $associationNames = $classMetaData->getAssociationNames();

        if ($dbNames) {
            foreach ($values as $key => $value) {
                if ($fieldNames && !$this->offsetExists($key)) {
                    $key = ucfirst($key);
                }
                $this[$key] = $value;
            }
        } else {
            foreach ($values as $key => $value) {
                if (in_array($key, $fieldNames) || (!$nurDbAttribute && in_array($key, $associationNames))) {
                    $methodName = "set" . ucfirst($key);
                    if (method_exists($this, $methodName)) {
                        $this->{$methodName}($value);
                    }
                }
            }
        }
    }

    /**
     * gibt alle Werte (die auch in der DB gespeichert werden) als Array zurück
     * @param $db_names Wenn gesetzt, gib Namen der DB zurück,
     * statt im Mapping verwendete Namen
     */
    public function getValues($db_names = false, $dateToString = false, $toFieldName = true) {
        $classMetaData = self::getClassMetaData();
        $fieldNames = $classMetaData->getFieldNames();
        $associationNames = $classMetaData->getAssociationNames();

        $arr = array();
        $fieldsToExport = array($fieldNames);
        // wenn Objekt exportiert wird, exportiere auch Assoz., 
        // sonst nur DB-Daten
        if (!$db_names) {
            $fieldsToExport[] = $associationNames;
        }

        foreach ($fieldsToExport as $names) {
            foreach ($names as $fieldName) {
                //           if ($this->offsetExists($fieldName)) {
                $array_field_name = $fieldName;
                if ($db_names) {
                    $array_field_name = $classMetaData->getColumnName($fieldName);
                    if ($toFieldName) {
                        $array_field_name = lcfirst($array_field_name);
                    }
                }
                //$value = $this->offsetGet($fieldName);
                $value = $this->{"get" . ucfirst($fieldName)}();
                if ($dateToString) {
                    if (is_a($value, "Date") || is_a($value, "DateTime") && $value->format("H:i:s") == "00:00:00") {
                        $value = $value->format("Y-m-d");
                    } elseif (is_a($value, "DateTime")) {
                        $value = $value->format("Y-m-d H:i:s");
                    }
                }
                $arr[$array_field_name] = $value;
                //           }
            }
        }
        return $arr;
    }

    /**
     * returns an array with all keys as column names in the DB
     * -> In Formularen werden meist die Werte wie in der DB verwendet
     */
    public function toDbColumnArray($dateToString = true) {
        return $this->getValues($db_names = true, $dateToString, false);
    }

    /**
     * returns an array with all keys as in the forms (= as in the DB, but first letter small)
     * 
     */
    public function toFieldColumnArray($dateToString = true) {
        return $this->getValues($db_names = true, $dateToString, $toFieldNames = true);
    }

    /**
     * --@ORM\PrePersist
     */
    public function setCreationTimestamp() {
        if (!$this->_noDates) {
            $this->setCreatedAt(new DateTime());
        }
    }

    /**
     * --@ORM\PreUpdate
     */
    public function setUpdateTimestamp() {
        if (!$this->_noDates) {
            $this->setUpdatedAt(new DateTime());
        }
    }

    /**
     * Hook updating a field in the DB
     * -> use as postUpdate callback
     * 
     * Finds differences between the current object and the initial values and
     * saves the differences to the DB
     */
    public function logChangeToDb($action = "update") {
        if ($this->_noLog) {
            return;
        }

        $TYPE_CREATED = 1;
        $TYPE_UPDATED = 20;
        $TYPE_DELETED = 50;

        // load original object
        $identifier = $this->getDbIdentifier();
        // don't log anything, if there is no primary key in the DB
        if (!$identifier) {
            return;
        }

        $classMetaData = self::getClassMetadata();

        if ($action == "delete" || $action == "create") {
            // Creation and deletion
            $changeObject = new LogDbChange();
            $changeObject->setTableName($classMetaData->getTableName());
            $changeObject->setEntityName(preg_replace("/.*\\\\/", "", get_class($this)));
            $changeObject->setTableId($identifier);
            $changeObject->setType($action == "delete" ? $TYPE_DELETED : $TYPE_CREATED);
            $changeObject->fillValues();

            if ($action == "create") {
                // save starting values as comment
                $this->refresh();
                $changeObject->setDataComment(print_r($this->toDbColumnArray(), 1));
            }

            $changeObject->save();
        } else {
            // New entry: save all values
            $changeList = self::getEntityManager()->getUnitOfWork()->getEntityChangeset($this);
            $classMetaData = self::getClassMetaData();

            $fieldNames = $classMetaData->getFieldNames();
            // log only real fields, no associations (can/should be changed to log also association IDs)
            foreach ($changeList as $fieldName => $changesArray) {
                if (!in_array($fieldName, $fieldNames)) {
                    continue;
                }
                // never log changes of the "updatedAt" field
                if (in_array($fieldName, array("updatedAt"))) {
                    continue;
                }
                $oldVal = $changesArray[0];
                $newVal = $changesArray[1];
                if ($oldVal != $newVal) {
                    $changeObject = new LogDbChange();
                    $changeObject->setTableName($classMetaData->getTableName());
                    $changeObject->setEntityName(preg_replace("/.*\\\\/", "", get_class($this)));
                    $changeObject->setTableId($identifier);
                    $changeObject->setFieldName($fieldName);
                    $changeObject->setType($TYPE_UPDATED);
                    $changeObject->setTableColumn($classMetaData->getColumnName($fieldName));
                    $changeObject->setOldValue(print_r($oldVal, 1));
                    $changeObject->setNewValue(print_r($newVal, 1));
                    $changeObject->fillValues();

                    $changeObject->save();
                }
            }
        }
    }

    /* Hook deleting an entity (Use as preRemove callback)*/

    public function logDeleteToDb() {
        $this->logChangeToDb("delete");
    }

    /* Hook creating an entity (use as postPersist callback) */

    public function logCreationToDb() {
        $this->logChangeToDb("create");
    }

    /**
     * Beim Loggen der Änderungen in die DB: hier kann zusätzlicher Wert für "user_value" 
     * geschrieben werden, so dass man zusätzlich zum eingeloggten Benutzer einen Kommentar
     * verwenden kann.
     * Z.B. "automatisch" oder "Modulduplizierung" 
     *   -> damit im Log klar wird, dass die Änderung nicht manuell vom Benutzer ausgeführt wurde.
     */
    public static function set_UserComment($userComment) {
        self::$_userComment = $userComment;
    }

    public static function get_UserComment() {
        return self::$_userComment;
    }

    /**
     * Gibt den Namen der DB-tabelle zurück, in der diese Entität gespeichert wird 
     */
    public function getDbTableName() {
        return self::getEntityManager()->getClassMetadata(get_class($this))->getTableName();
    }

    private static $db_latest_version = null;

    /**
     * get the latest migration version from the DB
     * -> the retreived version string represents a date/time and
     *    is equivalent to a certain DB structure
     */
    public static function getLatestMigrationVersion() {
        if (self::$db_latest_version) {
            return self::$db_latest_version;
        } else {
            self::$db_latest_version = self::getEntityManager()->getConnection()->executeQuery("SELECT MAX(version) FROM migration_version_doctrine")->fetchColumn();
            return self::$db_latest_version;
        }
    }

    /**
     * Umwandeln eines Datumsstrings in ein PHP Date-Objekt
     */
    public static function dateToObject($string) {
        return new DateTime($string);
    }
    

}
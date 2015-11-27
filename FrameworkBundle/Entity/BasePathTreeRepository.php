<?php

namespace LooopCore\FrameworkBundle\Entity;

use Gedmo\Tree\Entity\Repository\MaterializedPathRepository;
use Gedmo\Tree\TreeListener;
use LLPTest\TreeBundle\Entity\PathTree;

/**
 * Die Superklasse für alle Entitäts-Repositories. Hier können Methoden festgelegt werden,
 * die ohne eine konkrete Entity aufgerufen werden können, sowie spezielle Methoden zum Speiuchern etc.
 */
class BasePathTreeRepository extends MaterializedPathRepository {
    use RepositoryTrait;
    
    /**
     * repairs the tree of this repository, which means sets all 
     * "level" and "path" columns of all entries in the DB.
     * 
     * Uses pure SQL *without* lifecycle callbacks etc
     * 
     * @param integer $maxLevel (optional) the maximum levels the tree can have (needed for iterations)
     */
    public function repairTree($maxLevel = 30) {
        
        # get Config for this repository
        
        /* @var $repoListener TreeListener */
        $repoListener = $this->listener;
        $em = $this->getEntityManager();
        $entityClassName = $this->getEntityName();
        $config = $repoListener->getConfiguration($em, $entityClassName);
        
        $tableName = $this->getClassMetadata()->getTableName();
        $idColumn = $this->getClassMetadata()->getIdentifierColumnNames()[0];
        $idField = $this->getClassMetadata()->getFieldForColumn($idColumn);
        $pathColumn = $config["path"];
        $pathField = $this->getClassMetadata()->getFieldForColumn($pathColumn);
        $levelColumn = $config["level"];
        $levelField = $this->getClassMetadata()->getFieldForColumn($levelColumn);
        $parentColumn = $config["parent"];
        $parentField = $this->getClassMetaData()->getAssociationMapping($parentColumn)["joinColumns"][0]["name"];
        $pathSourceColumn = $config["path_source"];
        $pathSourceField = $this->getClassMetadata()->getFieldForColumn($pathSourceColumn);
        $separator = $config["path_separator"];
        
        $connection = $this->getEntityManager()->getConnection();
        
        # Repair treeLevel in level 1
        $sql1 = "UPDATE `$tableName` SET `$levelField` = 1 WHERE $parentField IS NULL ";
        $connection->prepare($sql1)->execute();
        
        # Repair treeLevel in levels > 1 -> must be executed many times
        foreach (range(0, $maxLevel) as $__repeated) {
            $sql2 = "UPDATE `$tableName` AS child "
                    . " INNER JOIN `$tableName` AS parent ON (child.`$parentField` = parent.`$idField` ) "
                    . " SET `child`.`$levelField` = `parent`.`$levelField` + 1 "
                    . " WHERE child.`$levelField` IS NULL  ";
            $connection->prepare($sql2)->execute();
        }

        # Repair treePath in level 1
        $sql3 = "UPDATE `$tableName` SET `$pathField` = CONCAT(`$pathSourceField`, '$separator') WHERE $parentField IS NULL ";
        $connection->prepare($sql3)->execute();
        
        # Repair treePath in levels > 1 -> must be executed many times
        foreach (range(0, $maxLevel) as $__repeated) {
            $sql4 = "UPDATE `$tableName` AS child "
                    . " INNER JOIN `$tableName` AS parent ON (child.`$parentField` = parent.`$idField` ) "
                    . " SET `child`.`$pathField` = CONCAT( `parent`.`$pathField`, `child`.`$pathSourceField`, '.' ) "
                    . " WHERE `parent`.$pathField IS NOT NULL";
            $connection->prepare($sql4)->execute();
        }

    }

}
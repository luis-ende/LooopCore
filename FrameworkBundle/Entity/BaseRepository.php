<?php

namespace LooopCore\FrameworkBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * Die Superklasse für alle Entitäts-Repositories. Hier können Methoden festgelegt werden,
 * die ohne eine konkrete Entity aufgerufen werden können, sowie spezielle Methoden zum Speiuchern etc.
 */
class BaseRepository extends EntityRepository {
    use RepositoryTrait;
    

}
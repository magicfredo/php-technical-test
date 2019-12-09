<?php

namespace App\Repository;

use App\Entity\Run;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Run|null find($id, $lockMode = null, $lockVersion = null)
 * @method Run|null findOneBy(array $criteria, array $orderBy = null)
 * @method Run[]    findAll()
 * @method Run[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RunRepository extends ServiceEntityRepository
{
    /**
     * @param ManagerRegistry $registry
     */
    public function __construct(
        ManagerRegistry $registry
    ) {
        parent::__construct($registry, Run::class);
    }
}

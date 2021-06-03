<?php

namespace App\Repository;

use App\Entity\Bets;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Bets|null find($id, $lockMode = null, $lockVersion = null)
 * @method Bets|null findOneBy(array $criteria, array $orderBy = null)
 * @method Bets[]    findAll()
 * @method Bets[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BetsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Bets::class);
    }
}

<?php

namespace App\Repository;

use App\Entity\BetsBonus;
use App\Entity\Teams;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method BetsBonus|null find($id, $lockMode = null, $lockVersion = null)
 * @method BetsBonus|null findOneBy(array $criteria, array $orderBy = null)
 * @method BetsBonus[]    findAll()
 * @method BetsBonus[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BetsBonusRepository extends ServiceEntityRepository
{
	public function __construct(ManagerRegistry $registry)
	{
		parent::__construct($registry, BetsBonus::class);
	}

	public function getUserBonuses(int $userId): ?array
	{
		return $this->createQueryBuilder('bb')
			->select(
				't.name',
				't.id',
				'bb.topScorer',
				'bb.points',
				'bb.id as bonusId'
			)
			->leftJoin(Teams::class, 't', 'WITH', 't.id = bb.team')
			->where('bb.user = :userId')
			->setParameter('userId', $userId)
			->getQuery()
			->getOneOrNullResult();
	}

	public function getUserBonus(int $userId, int $betId): BetsBonus
	{
		return $this->createQueryBuilder('bb')
			->where('bb.user = :userId')
			->andWhere('bb.id = :betId')
			->setParameter('userId', $userId)
			->setParameter('betId', $betId)
			->getQuery()
			->getSingleResult();
	}
}

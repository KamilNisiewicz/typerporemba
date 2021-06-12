<?php

namespace App\Repository;

use App\Entity\Matches;
use App\Entity\Teams;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Matches|null find($id, $lockMode = null, $lockVersion = null)
 * @method Matches|null findOneBy(array $criteria, array $orderBy = null)
 * @method Matches[]    findAll()
 * @method Matches[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MatchesRepository extends ServiceEntityRepository
{
	public function __construct(ManagerRegistry $registry)
	{
		parent::__construct($registry, Matches::class);
	}

	public function getSchedule(): array
	{
		$schedule = [];

		$schedule = $this->createQueryBuilder('m')
			->select(
				't1.name as homeTeamName',
				't2.name as awayTeamName',
				'm.phase',
				'm.date',
				't1.flagPath as homeTeamFlag',
				't2.flagPath as awayTeamFlag'
			)
			->leftJoin(Teams::class, 't1', 'WITH', 't1.id = m.homeTeam')
			->leftJoin(Teams::class, 't2', 'WITH', 't2.id = m.awayTeam')
			->orderBy('m.date', 'ASC')
			->getQuery()
			->getArrayResult();

		return $schedule;
	}

	public function getEmptyMatches()
	{
		$now = date('Y-m-d H:i:s');

		return $this->createQueryBuilder('m')
			->select(
				't1.name as homeTeamName',
				't2.name as awayTeamName',
				'm.id',
				'm.homeTeamScore',
				'm.awayTeamScore',
				't1.flagPath as homeTeamFlag',
				't2.flagPath as awayTeamFlag'
			)
			->leftJoin(Teams::class, 't1', 'WITH', 't1.id = m.homeTeam')
			->leftJoin(Teams::class, 't2', 'WITH', 't2.id = m.awayTeam')
			->where('m.finished = false')
			->andWhere('m.date < :date')
			->setParameter('date', $now)
			->getQuery()
			->getArrayResult();
	}
}

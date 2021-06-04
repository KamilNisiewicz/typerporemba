<?php

namespace App\Repository;

use App\Entity\Bets;
use App\Entity\Matches;
use App\Entity\Teams;
use App\Entity\Users;
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

    public function getResults(): array
    {
	return $this->createQueryBuilder('b')
	    ->select('sum(b.points) as points',
		'u.id',
		'u.login')
	    ->leftJoin(Users::class, 'u', 'WITH', 'u.id = b.user')
	    ->groupBy('u.id', 'u.login')
	    ->orderBy('points', 'DESC')
	    ->getQuery()
	    ->getArrayResult();
    }

    public function sumUserPoints(int $id): array
    {
	$now = date("Y-m-d H:i:s");

	return $this->createQueryBuilder('b')
	    ->select('sum(b.points) as points')
	    ->leftJoin(Matches::class, 'm', 'WITH', 'm.id = b.match')
	    ->where('b.user = :id')
	    ->andWhere('m.date < :date')
	    ->setParameter('id', $id)
	    ->setParameter('date', $now)
	    ->getQuery()
	    ->getSingleResult();
    }

    public function getUserResultsDetails(int $id): array
    {
	$results = [];
	$now = date("Y-m-d H:i:s");

	$results = $this->createQueryBuilder('b')
	    ->select('t1.name as homeTeamName',
		't2.name as awayTeamName',
		'm.phase',
		'm.date',
		'm.homeTeamScore',
		'm.awayTeamScore',
		'b.homeTeamScore as userHomeTeamScore',
		'b.awayTeamScore as userAwayTeamScore',
		'b.points',
		't1.flagPath as homeTeamFlag',
		't2.flagPath as awayTeamFlag'
	    )
	    ->leftJoin(Matches::class, 'm', 'WITH', 'm.id = b.match')
	    ->leftJoin(Teams::class, 't1', 'WITH', 't1.id = m.homeTeam')
	    ->leftJoin(Teams::class, 't2', 'WITH', 't2.id = m.awayTeam')
	    ->where('b.user = :id')
	    ->andWhere('m.date < :date')
	    ->setParameter('id', $id)
	    ->setParameter('date', $now)
	    ->orderBy('m.date', 'ASC')
	    ->getQuery()
	    ->getArrayResult();
	
	return $results;
    }
}

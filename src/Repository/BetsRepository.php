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

    public function sumUserPoints(int $userId): array
    {
	$now = date("Y-m-d H:i:s");

	return $this->createQueryBuilder('b')
	    ->select('sum(b.points) as points')
	    ->leftJoin(Matches::class, 'm', 'WITH', 'm.id = b.match')
	    ->where('b.user = :id')
	    ->andWhere('m.date < :date')
	    ->setParameter('id', $userId)
	    ->setParameter('date', $now)
	    ->getQuery()
	    ->getSingleResult();
    }

    public function getUserMatches(int $userId, string $type): array
    {
	$results = [];
	$date = "";
	$where = "";

	switch ($type) {
	    case 'finished':
	    $date = date('Y-m-d H:i:s');
	    $where = "m.date < '$date'";
	    break;
	    case 'today':
	    $from = date('Y-m-d') . ' 00:00:00';
	    $to   = date('Y-m-d') . ' 23:59:59';
	    $where = "m.date BETWEEN '$from' AND '$to'";
	    break;
	    case 'tomorrow':
	    $from = date('Y-m-d', strtotime('+1 day')) . ' 00:00:00';
	    $to   = date('Y-m-d', strtotime('+1 day')) . ' 23:59:59';
	    $where = "m.date BETWEEN '$from' AND '$to'";
	    break;
	    case 'rest':
	    $from = date('Y-m-d', strtotime('+2 day')) . ' 00:00:00';
	    $where = "m.date >= '$from'";
	    break;
	    case 'current':
	    $date = date('Y-m-d H:i:s');
	    $where = "m.date >= '$date'";
	    break;
	}

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
		'b.id as betId',
		't1.flagPath as homeTeamFlag',
		't2.flagPath as awayTeamFlag'
	    )
	    ->leftJoin(Matches::class, 'm', 'WITH', 'm.id = b.match')
	    ->leftJoin(Teams::class, 't1', 'WITH', 't1.id = m.homeTeam')
	    ->leftJoin(Teams::class, 't2', 'WITH', 't2.id = m.awayTeam')
	    ->where('b.user = :id')
	    ->andWhere($where)
	    ->setParameter('id', $userId)
	    ->orderBy('m.date', 'ASC')
	    ->getQuery()
	    ->getArrayResult();
	
	return $results;
    }

    public function getUserMatch(int $userId, int $betId): array
    {
	$results = [];

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
		'b.id as betId',
		't1.flagPath as homeTeamFlag',
		't2.flagPath as awayTeamFlag'
	    )
	    ->leftJoin(Matches::class, 'm', 'WITH', 'm.id = b.match')
	    ->leftJoin(Teams::class, 't1', 'WITH', 't1.id = m.homeTeam')
	    ->leftJoin(Teams::class, 't2', 'WITH', 't2.id = m.awayTeam')
	    ->where('b.user = :id')
	    ->andWhere('b.id = :betId')
	    ->setParameter('id', $userId)
	    ->setParameter('betId', $betId)
	    ->orderBy('m.date', 'ASC')
	    ->getQuery()
	    ->getArrayResult();
	
	return $results;
    }
}

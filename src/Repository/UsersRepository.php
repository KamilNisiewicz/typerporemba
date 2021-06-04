<?php

namespace App\Repository;

use App\Entity\Users;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Users|null find($id, $lockMode = null, $lockVersion = null)
 * @method Users|null findOneBy(array $criteria, array $orderBy = null)
 * @method Users[]    findAll()
 * @method Users[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UsersRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Users::class);
    }
    
    public function checkUsersExists(string $email, string $login): bool
    {
	$users = $this->createQueryBuilder('u')
	    ->select('u.login')
	    ->where('u.email = :email')
	    ->orWhere('u.login = :login')
	    ->setParameter('email', $email)
	    ->setParameter('login', $login)
	    ->getQuery()
	    ->getArrayResult();
	
	if($users){
	    return true;
	}else{
	    return false;
	}
    }

    public function searchUser(string $search): Users
    {
	$user = $this->createQueryBuilder('u')
	    ->where('u.email = :search')
	    ->orWhere('u.login = :search')
	    ->setParameter('search', $search)
	    ->getQuery()
	    ->getSingleResult();
	
	return $user;
    }
}

<?php

namespace App\Repository;

use App\Entity\School;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method School|null find($id, $lockMode = null, $lockVersion = null)
 * @method School|null findOneBy(array $criteria, array $orderBy = null)
 * @method School[]    findAll()
 * @method School[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SchoolRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, School::class);
    }

	/**
	* @return School[] Returns an array of School objects
	*/
    public function selectList($page)
    {
    	$onPage = 100;
    	$page = $page - 1;
    	$page = $page * $onPage;

        return $this->createQueryBuilder('s')
            // ->andWhere('s.id = :val')
            // ->setParameter('val', $value)
			->select('s.id, s.name, s.description, COUNT(st) as studentCount')
			->leftJoin('s.students', 'st')
            ->orderBy('s.id', 'ASC')
			->addGroupBy('s.id')
			->setFirstResult($page)
			->setMaxResults($onPage)
            ->getQuery()
			->useQueryCache(true)
			->useResultCache(true, 300)
            ->getScalarResult()
        ;
    }

    /*
    public function findOneBySomeField($value): ?School
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

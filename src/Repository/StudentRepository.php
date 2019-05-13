<?php

namespace App\Repository;

use App\Entity\Student;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Student|null find($id, $lockMode = null, $lockVersion = null)
 * @method Student|null findOneBy(array $criteria, array $orderBy = null)
 * @method Student[]    findAll()
 * @method Student[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StudentRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Student::class);
    }

     /**
      * @return Student[] Returns an array of Student objects
      */
	public function selectList($page)
	{
		$onPage = 100;
		$page = $page - 1;
		$page = $page * $onPage;

		return $this->createQueryBuilder('s')
			// ->andWhere('s.id = :val')
			// ->setParameter('val', $value)
			->orderBy('s.id', 'ASC')
			->setFirstResult($page)
			->setMaxResults($onPage)
			->getQuery()
			->getResult()
			;
	}

    /*
    public function findOneBySomeField($value): ?Student
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

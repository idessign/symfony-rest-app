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

		return $this->createQueryBuilder('st')
			// ->andWhere('s.id = :val')
			// ->setParameter('val', $value)
			->select('st.id, st.name')
			->orderBy('st.id', 'ASC')
			->setFirstResult($page)
			->setMaxResults($onPage)
			->getQuery()
			->getResult()
			;
	}

	public function show($id)
	{
		return $this->createQueryBuilder('st')
			->select('st.id, st.name')
			->andWhere('st.id = :id')
			->setParameter('id', $id)
			->getQuery()
			->getResult()
			;
	}
}

<?php

namespace App\Repository;

use App\Entity\ProductPerso;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ProductPerso|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProductPerso|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProductPerso[]    findAll()
 * @method ProductPerso[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductPersoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProductPerso::class);
    }

    // /**
    //  * @return ProductPerso[] Returns an array of ProductPerso objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */
    public function getAllProductOrderByExpirationDate($userId)
    {

        $builder = $this->createQueryBuilder('productPerso');

        $builder->where('productPerso.user = :productPersoUser');

        $builder->setParameter('productPersoUser', $userId);

        $builder->orderBy('productPerso.expiration_date', 'ASC');

        $query = $builder->getQuery();

        return $query->getResult();
    }
    public function getAllProductOrderByNameAsc($userId)
    {

        $builder = $this->createQueryBuilder('productPerso');

        $builder->where('productPerso.user = :productPersoUser');

        $builder->setParameter('productPersoUser', $userId);

        $builder->orderBy('productPerso.name', 'ASC');

        $query = $builder->getQuery();

        return $query->getResult();
    }
    public function getAllProductOrderByNameDesc($userId)
    {

        $builder = $this->createQueryBuilder('productPerso');

        $builder->where('productPerso.user = :productPersoUser');

        $builder->setParameter('productPersoUser', $userId);

        $builder->orderBy('productPerso.name', 'DESC');

        $query = $builder->getQuery();

        return $query->getResult();
    }

    /*
    public function findOneBySomeField($value): ?ProductPerso
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

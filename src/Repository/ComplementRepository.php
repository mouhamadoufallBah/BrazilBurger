<?php

namespace App\Repository;


use App\Entity\Complement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Complement|null find($id, $lockMode = null, $lockVersion = null)
 * @method Complement|null findOneBy(array $criteria, array $orderBy = null)
 * @method Complement[]    findAll()
 * @method Complement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ComplementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Complement::class);
    }

    /**
     * @return Complement
     */
    public function findLatest(): array
    {
        return $this->createQueryBuilder('c')
            ->where('c.prix > 0')
            ->setMaxResults(5)
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Query
     */
    public function findAllComplementQuery(): Query
    {
        return $this->createQueryBuilder('c')
            ->where('c.prix > 0')
            ->getQuery();
    }

    // /**
    //  * @return Complement[] Returns an array of Complement objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Complement
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

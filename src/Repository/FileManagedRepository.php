<?php

namespace App\Repository;

use App\Entity\FileManaged;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method FileManaged|null find($id, $lockMode = null, $lockVersion = null)
 * @method FileManaged|null findOneBy(array $criteria, array $orderBy = null)
 * @method FileManaged[]    findAll()
 * @method FileManaged[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FileManagedRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FileManaged::class);
    }

    // /**
    //  * @return FileManaged[] Returns an array of FileManaged objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?FileManaged
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

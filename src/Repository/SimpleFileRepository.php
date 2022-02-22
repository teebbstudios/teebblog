<?php

namespace App\Repository;

use App\Entity\SimpleFile;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SimpleFile|null find($id, $lockMode = null, $lockVersion = null)
 * @method SimpleFile|null findOneBy(array $criteria, array $orderBy = null)
 * @method SimpleFile[]    findAll()
 * @method SimpleFile[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SimpleFileRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SimpleFile::class);
    }

    // /**
    //  * @return SimpleFile[] Returns an array of SimpleFile objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?SimpleFile
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

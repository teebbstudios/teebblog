<?php

namespace App\Repository;

use App\Entity\Post;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Post|null find($id, $lockMode = null, $lockVersion = null)
 * @method Post|null findOneBy(array $criteria, array $orderBy = null)
 * @method Post[]    findAll()
 * @method Post[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Post::class);
    }

    /**
     * @return Post[] Returns an array of Post objects
     */
    public function findByStatus(string $value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.status = :value')
            ->setParameter('value', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();
    }

    public function findByTitle(string $keywords)
    {
        $qb = $this->createQueryBuilder('p');
//        return $qb->andWhere('p.title LIKE :keywords')
        return $qb->andWhere($qb->expr()->like('p.title', ':keywords'))
            ->setParameter('keywords', '%' . $keywords . '%')
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();
    }

    public function findByTitleDQL(string $keywords)
    {
        return $this->_em->createQuery('SELECT p FROM App\Entity\Post p WHERE p.title LIKE :keywords ORDER BY p.id DESC')
            ->setParameter('keywords', '%' . $keywords . '%')
            ->setMaxResults(10)
            ->getResult();
    }

    public function getPostPaginator(int $offset,  int $limit = 10, string $status='published'): Paginator
    {
        $query = $this->createQueryBuilder('p')
            ->andWhere('p.status LIKE :status')
            ->setParameter('status', '%'.$status.'%')
            ->orderBy('p.id', 'DESC')
            ->setMaxResults($limit)
            ->setFirstResult($offset)
            ->getQuery();

        return new Paginator($query);
    }

    /*
    public function findOneBySomeField($value): ?Post
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

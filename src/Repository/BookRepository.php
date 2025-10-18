<?php

namespace App\Repository;

use App\Entity\Book;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Book>
 */
class BookRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Book::class);
    }

    //    /**
    //     * @return Book[] Returns an array of Book objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('b')
    //            ->andWhere('b.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('b.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Book
    //    {
    //        return $this->createQueryBuilder('b')
    //            ->andWhere('b.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }




// Dans BookRepository.php
public function findBooksBetweenDates(\DateTime $startDate, \DateTime $endDate): array
{
    return $this->createQueryBuilder('b')
        ->select('b.title', 'b.publicationDate', 'b.category', 'a.username as authorName')
        ->leftJoin('b.author', 'a')
        ->where('b.publicationDate BETWEEN :start AND :end')
        ->setParameter('start', $startDate)
        ->setParameter('end', $endDate)
        ->orderBy('b.publicationDate', 'ASC')
        ->getQuery()
        ->getResult();
}

public function countRomanceBooks(): int
{
    return $this->createQueryBuilder('b')
        ->select('COUNT(b.id)')
        ->where('b.category = :category')
        ->setParameter('category', 'Romance')
        ->getQuery()
        ->getSingleScalarResult();
}

}

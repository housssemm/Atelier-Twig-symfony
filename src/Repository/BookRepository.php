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
    public function trie()
    {

        $em = $this->getEntityManager();

        $query = $em->createQuery("Select b from App\Entity\Book b order By b.title desc  ");
        return $query->getResult();
    }
    public function getNbRomance()
    {
        $em = $this->getEntityManager();

        $query = $em->createQuery("Select count(b)  from App\Entity\Book b where b.category ='Science-Fiction'  ");
        return $query->getSingleScalarResult();
    }
    public function pub()
    {
        $em = $this->getEntityManager();

        $query = $em->createQuery("Select b from App\Entity\Book b where b.publicationDate between :d1 and :d2 ")->setParameter('d1', "2014-01-01")->setParameter('d2', "2018-12-31");
        return $query->getResult();
    }
    public function findById($id)
    {

        $req = $this->createQueryBuilder('b')
            ->where('b.id=:id')
            ->setParameter('id',$id)
            ->getQuery()
            ->getResult();

        return $req;
    }
    public function findBya()
    {

        $req = $this->createQueryBuilder('b')
            
            ->join('b.author' ,'a')
            ->orderBy('a.username')
            ->getQuery()
            ->getResult();

        return $req;
    }

    public function findByYear($year,$nb)
    {

        $req = $this->createQueryBuilder('b')

            ->join('b.author', 'a')
            ->where('b.publicationDate<:year')
            ->andWhere('a.nb_books<:nb')
            ->setParameter('year',$year)
            ->setParameter('nb',$nb)
            ->getQuery()
            ->getResult();

        return $req;
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
}

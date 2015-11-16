<?php

namespace AppBundle\Entity;

use Pagerfanta\Adapter\DoctrineORMAdapter;

class FortuneRepository extends \Doctrine\ORM\EntityRepository
{
  public function findLasts() {
    $queryBuilder = $this->createQueryBuilder('F')
      ->where("F.validate = :validate")
      ->setParameter("validate", 1)
      ->orderBy("F.createdAt", "DESC");

      $adapter = new DoctrineORMAdapter($queryBuilder);

      return $adapter;
  }

  public function bestRated() {
    return $this->createQueryBuilder('F')
      ->orderBy("F.upVote/F.downVote", "DESC")
      ->setMaxResults(10)
      ->getQuery()
      ->getResult();
  }

  public function worstRated() {
    return $this->createQueryBuilder('F')
      ->orderBy("F.downVote/F.upVote", "DESC")
      ->setMaxResults(10)
      ->getQuery()
      ->getResult();
  }

  public function findByAuthor($idAuthor) {
    return $this->createQueryBuilder('F')
      ->where("F.user = :idAuthor")
      ->setParameter("idAuthor", $idAuthor)
      ->orderBy("F.createdAt", "DESC")
      ->setMaxResults(10)
      ->getQuery()
      ->getResult();
  }

  public function findRandomQuote() {

    $count = $this->createQueryBuilder('F')
             ->select('COUNT(F)')
             ->getQuery()
             ->getSingleScalarResult();

    return $this->createQueryBuilder('F')
             ->setFirstResult(rand(0, $count - 1))
             ->setMaxResults(1)
             ->getQuery()
             ->getSingleResult();
  }

  public function findModerated() {
    return $this->createQueryBuilder('F')
      ->where("F.validate = :validate")
      ->setParameter("validate", 0)
      ->orderBy("F.createdAt", "DESC")
      ->setMaxResults(1)
      ->getQuery()
      ->getResult();
  }
  
}

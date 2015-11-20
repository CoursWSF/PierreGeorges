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
    return array_column(
      $this->createQueryBuilder('F')
        ->select("F AS fortune")
        ->addSelect("F.upVote/(CASE WHEN F.downVote > 0 THEN F.downVote ELSE 1 END) AS ratio")
        ->orderBy("ratio", "DESC")
        ->setMaxResults(10)
        ->getQuery()
        ->getResult(),
      'fortune'
    );
  }

  public function worstRated() {
    return array_column(
      $this->createQueryBuilder('F')
        ->select("F AS fortune")
        ->addSelect("F.downVote/(CASE WHEN F.upVote > 0 THEN F.upVote ELSE 1 END) AS ratio")
        ->orderBy("ratio", "DESC")
        ->setMaxResults(10)
        ->getQuery()
        ->getResult(),
      'fortune'
    );
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

<?php

namespace AppBundle\Entity;

class CommentRepository extends \Doctrine\ORM\EntityRepository
{
  public function bestRated($fortune) {
    return array_column(
      $this->createQueryBuilder('C')
        ->select("C AS comment")
        ->addSelect("C.upVote/(CASE WHEN C.downVote > 0 THEN C.downVote ELSE 1 END) AS ratio")
        ->orderBy("ratio", "DESC")
        ->where("C.fortune = :fortune")
        ->setParameter("fortune", $fortune)
        ->getQuery()
        ->getResult(),
      'comment'
    );
  }
}

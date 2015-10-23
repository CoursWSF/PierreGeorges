<?php

namespace AppBundle\Entity;

class CommentRepository extends \Doctrine\ORM\EntityRepository
{
  public function bestRated($fortune) {
    return $this->createQueryBuilder('C')
      ->orderBy("C.upVote/C.downVote", "DESC")
      ->where("C.fortune = :fortune")
      ->setParameter("fortune", $fortune)
      ->getQuery()
      ->getResult();
  }

}

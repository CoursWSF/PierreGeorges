<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class FortuneType extends AbstractType
{
  public function getName()
  {
    return "Fortune";
  }

  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder
      ->add('title')
      ->add('content')
      ->add('submit', 'submit');
  }
}

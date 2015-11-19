<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

// RESPONSE JSON
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
USE Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

use AppBundle\Entity\Fortune;
use AppBundle\Entity\Comment;
use AppBundle\Entity\User;
use AppBundle\Form\FortuneType;
use AppBundle\Form\CommentType;
use Pagerfanta\Pagerfanta;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
      $user = $this->get('security.context')->getToken()->getUser();

      $fortunes = $this->getDoctrine()->getRepository("AppBundle:Fortune")->findLasts();
      $pagerfanta = new Pagerfanta($fortunes);
      $pagerfanta -> setCurrentPage($request->get("page", 1));

      return $this->render('default/index.html.twig', array(
          'base_dir' => realpath($this->container->getParameter('kernel.root_dir').'/..'),
          'quotes' => $pagerfanta,
          'user' => $user,
          'session' => $this->get("session")
      ));
    }

    /**
     * @Route("/voteup/{id}", name="voteup")
     */
    public function voteUpAction(Request $request, $id)
    {
      if (!$this->get("session")->has("idQuote".$id)) {
        $quote = $this->getDoctrine()->getRepository("AppBundle:Fortune")->find($id);
        $quote->voteUp();

        $this->get("session")->set("idQuote".$id, "voteUp");

        $this->getDoctrine()->getManager()->Flush();
        return $this->redirect($this->getRequest()->headers->get('referer'));
      }
      else {
        return $this->redirect($this->getRequest()->headers->get('referer'));
      }
    }

    /**
     * @Route("/votedown/{id}", name="votedown")
     */
    public function voteDownAction(Request $request, $id)
    {

      if (!$this->get("session")->has("idQuote".$id)) {
        $quote = $this->getDoctrine()->getRepository("AppBundle:Fortune")->find($id);
        $quote->voteDown();

        $this->get("session")->set("idQuote".$id, "voteDown");

        $this->getDoctrine()->getManager()->Flush();
        return $this->redirect($this->getRequest()->headers->get('referer'));
      }
      else {
        return $this->redirect($this->getRequest()->headers->get('referer'));
      }
    }

    /**
     * @Route("/top", name="top")
     */
     public function showBestRatedAction(Request $request)
     {
       return $this->render('default/top.html.twig', array(
           'bestQuotes' => $this->getDoctrine()->getRepository("AppBundle:Fortune")->bestRated(),
           'user' => $user = $this->get('security.context')->getToken()->getUser()
       ));
     }

     /**
      * @Route("/flop", name="flop")
      */
      public function showWorstRatedAction(Request $request)
      {
        return $this->render('default/flop.html.twig', array(
            'worstQuotes' => $this->getDoctrine()->getRepository("AppBundle:Fortune")->worstRated(),
            'user' => $user = $this->get('security.context')->getToken()->getUser()
        ));
      }

    /**
     * @Route("/byauthor/{idAuthor}", name="byauthor"),
     * @Method("GET"),
     * requirements={
     *    "_format": "json"
     * }
     */
    public function showByAuthorAction(Request $request, $idAuthor)
    {

      // RETURN JSON RESPONE
      // $quotesByAuthor = $this->getDoctrine()->getRepository("AppBundle:Fortune")->findByAuthor($idAuthor);
      // $serializedEntity = $this->container->get('serializer')->serialize($quotesByAuthor, 'json');
      // return new Response($serializedEntity, 200, array('Content-Type' => 'application/json'));

      $quotesByAuthor = $this->getDoctrine()->getRepository("AppBundle:Fortune")->findByAuthor($idAuthor);
      \dump($quotesByAuthor);

      return $this->render('default/byAuthor.html.twig', array(
          'quotesByAuthor' => $quotesByAuthor,
          'user' => $user = $this->get('security.context')->getToken()->getUser()
      ));
    }

    /**
     * @Route("/new", name="newquote")
     */
    public function createAction(Request $request)
    {
      $user = $this->get('security.context')->getToken()->getUser();
      $form = $this->createForm(new FortuneType, new Fortune);

      $form->handleRequest($request);
      if ($form->isValid()) {
        $em = $this->getDoctrine()->getManager();
        $quote = $form->getData();
        $quote -> setUser($user);
        $em->persist($quote);
        $em->flush();
        return $this->redirectToRoute('homepage');
      }

      return $this->render('default/newQuote.html.twig', array(
          'form' => $form->createView()
      ));
    }

    /**
     * @Route("/quote/{id}", name="onequote")
     */
    public function showOneQuoteAction(Fortune $fortune, Request $request, $id)
    {
      $user = $this->get('security.context')->getToken()->getUser();
      $form = $this->createForm(new CommentType, new Comment);

      $form->handleRequest($request);
      if ($form->isValid()) {
        $em = $this->getDoctrine()->getManager();
        $comment = $form->getData();
        $comment -> setFortune($fortune);
        $comment -> setUser($user);
        $em->persist($comment);
        $em->flush();
        return $this->redirect($this->getRequest()->headers->get('referer'));
      }

      return $this->render('default/oneQuote.html.twig', array(
          'quote' => $fortune,
          'comments' => $this->getDoctrine()->getRepository("AppBundle:Comment")->bestRated($fortune),
          'user' => $user,
          'form' => $form->createView()
      ));
    }

    /**
     * @Route("/random", name="random")
     */
    public function showRandomQuoteAction(Request $request)
    {
      $randomId = $this->getDoctrine()->getRepository("AppBundle:Fortune")->findRandomQuote()->getId();

      return $this->redirectToRoute('onequote', array('id' => $randomId));
    }

    /**
     * @Route("/voteup/comment/{id}", name="voteupcomment")
     */
    public function voteUpCommentAction(Request $request, $id)
    {
      $comment = $this->getDoctrine()->getRepository("AppBundle:Comment")->find($id);

      $comment->voteUp();

      $this->getDoctrine()->getManager()->Flush();
      return $this->redirect($this->getRequest()->headers->get('referer'));
    }

    /**
     * @Route("/votedown/comment/{id}", name="votedowncomment")
     */
    public function voteDownCommentAction(Request $request, $id)
    {
      $comment = $this->getDoctrine()->getRepository("AppBundle:Comment")->find($id);
      $comment->voteDown();

      $this->getDoctrine()->getManager()->Flush();
      return $this->redirect($this->getRequest()->headers->get('referer'));
    }

    public function showUserAction(Request $request)
    {
      return $this->render('default/_navbar.html.twig', array(
          'user' => $this->get('security.context')->getToken()->getUser()
      ));
    }

    /**
     * @Route("/moderate", name="moderate")
     */
    public function showModerateAction(Request $request)
    {

      return $this->render('default/moderation.html.twig', array(
        'quotes' => $this->getDoctrine()->getRepository("AppBundle:Fortune")->findModerated(),
        'user' => $this->get('security.context')->getToken()->getUser()
      ));
    }
      /**
       * @Route("/moderate/{id}", name="validateQuote")
       */
      public function validateQuoteAction(Request $request, $id)
      {
        $quote = $this->getDoctrine()->getRepository("AppBundle:Fortune")->find($id);

        $quote->setValidate();
        $this->getDoctrine()->getManager()->Flush();
        return $this->redirect($this->getRequest()->headers->get('referer'));
      }

      /**
       * @Route("/moderate/delete/{id}", name="deleteQuote")
       */
      public function deleteQuoteAction(Request $request, $id)
      {

        $em = $this->getDoctrine()->getManager();

        $quote = $this->getDoctrine()->getRepository("AppBundle:Fortune")->find($id);

        $em->remove($quote);
        $em->flush();

        return $this->redirect($this->getRequest()->headers->get('referer'));
      }


      /**
       * @Route("/edit/{id}", name="updateQuote")
       */
      public function updateQuoteAction(Fortune $fortune, Request $request, $id)
      {
        $user = $this->get('security.context')->getToken()->getUser();
        $form = $this->createForm(new FortuneType, $fortune);

        $form->handleRequest($request);
        if ($form->isValid()) {
          $em = $this->getDoctrine()->getManager();
          $comment = $form->getData();

          $em->persist($comment);
          $em->flush();
          return $this->redirectToRoute('onequote', array(
            'id' => $id
          ));
        }

        return $this->render('default/updateQuote.html.twig', array(
            'quote' => $fortune,
            'user'=> $user,
            'form' => $form->createView()
        ));
      }
}

<?php

namespace JC\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use JC\BlogBundle\Entity\Comment;
use JC\BlogBundle\Form\CommentType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;


/**
 * Comment controller.
 */
class CommentController extends Controller
{
    public function newAction($blog_id, Request $request)
    {
        $blog = $this->getBlog($blog_id);

        $comment = new Comment();
        $comment->setBlog($blog);
        $form = $this->createFormBuilder($comment)
            ->add('user', TextType::class)
            ->add('comment', TextType::class)
            ->add('save', SubmitType::class, array('label' => 'Send'))
            ->getForm();


        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $form->getData();

            $comment = $form->getData();

            $em = $this->getDoctrine()->getManager();
            $em->persist($comment);
            $em->flush();

            return $this->redirectToRoute('jc_blog_homepage');
        }

        return $this->render('@JCBlog/Comment/_form.html.twig', array(
            'comment' => $comment,
            'form'   => $form->createView()
        ));
    }

        public function createAction($blog_id, Request $request)
    {
        $blog = $this->getBlog($blog_id);

        $comment = new Comment();
        $comment->setBlog($blog);
        $form = $this->createFormBuilder($comment)
            ->add('user', TextType::class)
            ->add('comment', TextType::class)
            ->add('save', SubmitType::class, array('label' => 'Send'))
            ->getForm();


        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $form->getData();

            $comment = $form->getData();

            $em = $this->getDoctrine()->getManager();
            $em->persist($comment);
            $em->flush();

            return $this->redirect($this->generateUrl('jc_blog_show', array(
                    'id'    => $comment->getBlog()->getId(),
                    'slug'  => $comment->getBlog()->getSlug())) .
                '#comment-' . $comment->getId()
            );
        }

        return $this->render('@JCBlog/Comment/create.html.twig', array(
            'comment' => $comment,
            'form'    => $form->createView()
        ));
    }

    protected function getBlog($blog_id)
    {
        $em = $this->getDoctrine()
            ->getManager();

        $blog = $em->getRepository('JCBlogBundle:Blog')->find($blog_id);

        if (!$blog) {
            throw $this->createNotFoundException('Unable to find Blog post.');
        }

        return $blog;
    }

}
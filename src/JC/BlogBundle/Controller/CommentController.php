<?php

namespace JC\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use JC\BlogBundle\Entity\Comment;
use JC\BlogBundle\Form\CommentType;
use Symfony\Component\HttpFoundation\Request;


/**
 * Comment controller.
 */
class CommentController extends Controller
{
    public function newAction($blog_id)
    {
        $blog = $this->getBlog($blog_id);

        $comment = new Comment();
        $comment->setBlog($blog);

        $form = $this->get('form.factory')->create(CommentType::class, $comment);


        return $this->render('@JCBlog/Comment/_form.html.twig', array(
            'comment' => $comment,
            'form'   => $form->createView()
        ));
    }

    public function createAction($blog_id)
    {
        $blog = $this->getBlog($blog_id);

        $comment  = new Comment();
        $comment->setBlog($blog);
        $request = $this->getRequest();
        $form = $this->get('form.factory')->create(CommentType::class, $comment);
        $form->bind($request);

        if ($form->isValid()) {

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
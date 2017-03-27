<?php

namespace JC\BlogBundle\Controller;

use JC\BlogBundle\Entity\Blog;
use JC\BlogBundle\Entity\Tagss;
use JC\BlogBundle\Form\BlogType;
use JC\BlogBundle\Form\TagssType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use JC\BlogBundle\Entity\Comment;
use JC\BlogBundle\Form\CommentType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;


/**
 * Class ArticleController
 * @package JC\BlogBundle\Controller
 */
class ArticleController extends Controller
{

    public function newArticleAction(Request $request){

        $em = $this ->getDoctrine()
                ->getManager();


        $blog = new Blog();

        $blog_form = $this->createForm(BlogType::class, $blog);



        $blog_form->handleRequest($request);

        if ($blog_form->isSubmitted() && $blog_form->isValid()) {
            $blog_name = $blog_form->get('title')->getData();
            $blog ->setSlug($blog_name);

            $blog = $blog_form->getData();



            $blog_tag = $blog_form->get('tag')->getData();

            $tags = array();

            $tags = array_merge(explode(",", $blog_tag), $tags);


            $em->persist($blog);
            for( $x = 0; $x < count($tags); $x++)  {
                $tag = new Tagss();
                $tag ->setValue($tags[$x]);
                $tag ->setBlogId($blog);
                $em->persist($tag);
            }


            $em->flush();

            return $this->redirectToRoute('jc_blog_homepage');
        }


        return $this->render('@JCBlog/Article/index.html.twig', array(
            'form'     => $blog_form->createView(),

        ));

    }


}
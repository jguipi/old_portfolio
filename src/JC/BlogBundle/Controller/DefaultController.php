<?php

namespace JC\BlogBundle\Controller;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use JC\BlogBundle\Entity\Enquiry;
use JC\BlogBundle\Form\EnquiryType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class DefaultController extends Controller
{
    public function indexAction()
    {


        $em = $this->getDoctrine()
            ->getManager();

        $blogs = $em->getRepository('JCBlogBundle:Blog')
            ->getLatestBlogs();



        return $this->render('JCBlogBundle:Default:index.html.twig',array(
            'blogs' =>$blogs
        ));
    }

    public function aboutAction()
    {
        return $this->render('JCBlogBundle:Default:about.html.twig');
    }



    public function contactAction(Request $request)
    {
        // Create the form according to the FormType created previously.
        // And give the proper parameters
        $form = $this->createForm('JC\ProfileBundle\Form\ContactType',null,array(
            // To set the action use $this->generateUrl('route_identifier')
            'action' => $this->generateUrl('jc_blog_contact'),
            'method' => 'POST'
        ));

        if ($request->isMethod('POST')) {
            // Refill the fields in case the form is not valid.
            $form->handleRequest($request);

            if($form->isValid()){
                // Send mail
                if($this->sendEmail($form->getData())){

                    // Everything OK, redirect to wherever you want ! :

                    return $this->redirectToRoute('jc_blog_homepage');
                }else{
                    // An error ocurred, handle
                    var_dump("An error happen! Wasn't able to send the mail.");
                }
            }
        }

        return $this->render('@JCBlog/Default/contact.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * Show a blog entry
     */
    public function showAction($id, $slug)
    {
        $em = $this->getDoctrine()->getManager();

        $blog = $em->getRepository('JCBlogBundle:Blog')->find($id);

        if (!$blog) {
            throw $this->createNotFoundException('Unable to find Blog post.');
        }

        $comments = $em->getRepository('JCBlogBundle:Comment')
            ->getCommentsForBlog($blog->getId());

        return $this->render('JCBlogBundle:Default:show.html.twig', array(
            'blog'      => $blog,
            'comments'  => $comments
        ));
    }


    public function sidebarAction()
    {
        $em = $this ->getDoctrine()
                    ->getManager();

        $tags = $em ->getRepository('JCBlogBundle:Blog')
                    ->getTags();

        $tagWeights = $em   ->getRepository('JCBlogBundle:Blog')
                            ->getTagWeights($tags);

        $commentLimit   = $this ->container
                                ->getParameter('jc_blog.comments.latest_comment_limit');

        $latestComments = $em   ->getRepository('JCBlogBundle:Comment')
                                ->getLatestComments($commentLimit);

        return $this->render('@JCBlog/Default/sidebar.html.twig', array(
            'latestComments'    => $latestComments,
            'tags'              => $tagWeights
        ));
    }

    private function sendEmail($data){
        $myappContactMail = 'juste_g@hotmail.com';
        $myappContactPassword = 'Chocolat123';

        // In this case we'll use the ZOHO mail services.
        // If your service is another, then read the following article to know which smpt code to use and which port
        // http://ourcodeworld.com/articles/read/14/swiftmailer-send-mails-from-php-easily-and-effortlessly
        $transport = \Swift_SmtpTransport::newInstance('smtp-mail.outlook.com', 587,'tls')
            ->setUsername($myappContactMail)
            ->setPassword($myappContactPassword);

        $mailer = \Swift_Mailer::newInstance($transport);

        $message = \Swift_Message::newInstance("Site message from  ". $data["subject"])
            ->setFrom(array($myappContactMail => "Message by ".$data["name"]))
            ->setTo(array(
                $myappContactMail => $myappContactMail
            ))
            ->setBody($data["message"]."<br> ContactMail :".$data["email"]);

        return $mailer->send($message);
    }

}

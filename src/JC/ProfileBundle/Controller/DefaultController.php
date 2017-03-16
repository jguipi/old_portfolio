<?php

namespace JC\ProfileBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    public function indexAction(Request $request)
    {

        // Create the form according to the FormType created previously.
        // And give the proper parameters
        $form = $this->createForm('JC\ProfileBundle\Form\ContactType',null,array(
            // To set the action use $this->generateUrl('route_identifier')
            'action' => $this->generateUrl('jc_profile_homepage'),
            'method' => 'POST'
        ));

        if ($request->isMethod('POST')) {
            // Refill the fields in case the form is not valid.
            $form->handleRequest($request);

            if($form->isValid()){
                // Send mail
                if($this->sendEmail($form->getData())){

                    // Everything OK, redirect to wherever you want ! :

                    return $this->redirectToRoute('jc_profile_homepage');
                }else{
                    // An error ocurred, handle
                    var_dump("An error happen! Wasn't able to send the mail.");
                }
            }
        }

        return $this->render('@JCProfile/Default/index.html.twig', array(
            'form' => $form->createView()
        ));
    }

    public function indexFRAction(Request $request)
    {

        // Create the form according to the FormType created previously.
        // And give the proper parameters
        $form = $this->createForm('JC\ProfileBundle\Form\ContactFRType',null,array(
            // To set the action use $this->generateUrl('route_identifier')
            'action' => $this->generateUrl('jc_profile_homepage'),
            'method' => 'POST'
        ));

        if ($request->isMethod('POST')) {
            // Refill the fields in case the form is not valid.
            $form->handleRequest($request);

            if($form->isSubmitted() && $form->isValid()){
                // Send mail
                if($this->sendEmail($form->getData())){

                    // Everything OK, redirect to wherever you want ! :

                    return $this->redirectToRoute('jc_profile_homepage_fr');
                }else{
                    // An error ocurred, handle
                    var_dump("An error happen! Wasn't able to send the mail.");
                }
            }
        }

        return $this->render('@JCProfile/Default/indexFR.html.twig', array(
            'form' => $form->createView()
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

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
use JC\BlogBundle\Entity\Connectes;
use Symfony\Component\Validator\Constraints\Date;

class DefaultController extends Controller
{
    public function indexAction(Request $request)
    {

        $em = $this ->getDoctrine()
                    ->getManager();

        $blogs = $em->getRepository('JCBlogBundle:Blog')
                    ->getLatestBlogs();


        $ip = $request -> getClientIp();

        $repository = $this ->getDoctrine()
                            ->getRepository('JCBlogBundle:Connectes');

        $visitor_array = $repository
            ->findBy(array('ip' => $ip));

        $date_time = new \DateTime();
        $time_zone = new \DateTimeZone('America/Toronto');
        $date_time -> setTimezone($time_zone);


        if ($visitor_array = 0 || $visitor_array == 0 || is_null( $visitor_array ) || $visitor_array == null){

            $country = $this-> ip_info($ip, 'Country');
            $connectes = new Connectes();
            $connectes->setIp($ip);
            $connectes->setTimestamp($date_time);
            $connectes ->setPosition($country);



            $em->persist($connectes);
            $em->flush();
        }
        else{
            $visitor_id = $repository
                ->findOneBy(array('ip' => $ip))->getId();


            $country = $this-> ip_info($ip, 'Country');

            $client_to_update = $repository -> find($visitor_id);
            $client_to_update ->setTimestamp($date_time);
            $client_to_update ->setPosition($country);
            $em ->flush();

        }

        $client_number = $repository
                        ->findAll();

        $client_number_int = count($client_number);

        $blogs2 = $em->getRepository('JCBlogBundle:Blog')
            ->getSelectecTagBlog('nounou');

        dump($blogs2);

        return $this->render('JCBlogBundle:Default:index.html.twig',array(
            'blogs' =>$blogs,
            'count' => $client_number_int,
            'country' => $country,
        ));

    }

    public function tagAction($tag){

        $repository = $this ->getDoctrine()
            ->getRepository('JCBlogBundle:Connectes');

        $client_number = $repository
            ->findAll();

        $client_number_int = count($client_number);

        $em = $this ->getDoctrine()
            ->getManager();

        $blogs = $em->getRepository('JCBlogBundle:Blog')
            ->getSelectecTagBlog($tag);

        dump($blogs);

        return $this->render('JCBlogBundle:Default:index.html.twig', array(
            'blogs' => $blogs,
            'count' => $client_number_int
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


    public function ip_info($ip = NULL, $purpose = 'location', $deep_detect = TRUE) {
        $output = NULL;
        if (filter_var($ip, FILTER_VALIDATE_IP) === FALSE) {
            $ip = $_SERVER['REMOTE_ADDR'];
            if ($deep_detect) {
                if (filter_var(@$_SERVER['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP))
                    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
                if (filter_var(@$_SERVER['HTTP_CLIENT_IP'], FILTER_VALIDATE_IP))
                    $ip = $_SERVER['HTTP_CLIENT_IP'];
            }
        }
        $purpose    = str_replace(array('name', '\n', '\t', ' ', '-', '_'), NULL, strtolower(trim($purpose)));
        $support    = array('country', 'countrycode', 'state', 'region', 'city', 'location', 'address');
        $continents = array(
            'AF' => 'Africa',
            'AN' => 'Antarctica',
            'AS' => 'Asia',
            'EU' => 'Europe',
            'OC' => 'Australia (Oceania)',
            'NA' => 'North America',
            'SA' => 'South America'
        );
        if (filter_var($ip, FILTER_VALIDATE_IP) && in_array($purpose, $support)) {
            $ipdat = @json_decode(file_get_contents('http://www.geoplugin.net/json.gp?ip=' . $ip));
            if (@strlen(trim($ipdat->geoplugin_countryCode)) == 2) {
                switch ($purpose) {
                    case 'location':
                        $output = array(
                            'city'           => @$ipdat->geoplugin_city,
                            'state'          => @$ipdat->geoplugin_regionName,
                            'country'        => @$ipdat->geoplugin_countryName,
                            'country_code'   => @$ipdat->geoplugin_countryCode,
                            'continent'      => @$continents[strtoupper($ipdat->geoplugin_continentCode)],
                            'continent_code' => @$ipdat->geoplugin_continentCode
                        );
                        break;
                    case 'address':
                        $address = array($ipdat->geoplugin_countryName);
                        if (@strlen($ipdat->geoplugin_regionName) >= 1)
                            $address[] = $ipdat->geoplugin_regionName;
                        if (@strlen($ipdat->geoplugin_city) >= 1)
                            $address[] = $ipdat->geoplugin_city;
                        $output = implode(', ', array_reverse($address));
                        break;
                    case 'city':
                        $output = @$ipdat->geoplugin_city;
                        break;
                    case 'state':
                        $output = @$ipdat->geoplugin_regionName;
                        break;
                    case 'region':
                        $output = @$ipdat->geoplugin_regionName;
                        break;
                    case 'country':
                        $output = @$ipdat->geoplugin_countryName;
                        break;
                    case 'countrycode':
                        $output = @$ipdat->geoplugin_countryCode;
                        break;
                }
            }
        }
        return $output;
    }
}

<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $posts = $this->getDoctrine()
            ->getRepository('AppBundle:Post')
            ->findBy(array('is_published'=>1),array('created_time'=>'desc'));

        return $this->render('default/index.html.twig', array(
            'posts' => $posts,
        ));
    }
}

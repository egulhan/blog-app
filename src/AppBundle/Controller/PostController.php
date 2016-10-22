<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\Post;

class PostController extends Controller
{
    /**
     * Matches /post/*
     *
     * @Route("/post/{slug}", name="post_show")
     */
    public function showAction($slug)
    {
        $post = $this->getDoctrine()->getRepository('AppBundle:Post')->findOneBy(array(
            'seo_url' => $slug
        ));

        if (!$post) {
            throw $this->createNotFoundException('No post found for slug '.$slug);
        }

        return $this->render('post/show.html.twig',array(
            'post'=>$post,
        ));
    }
}
<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Post;
use Symfony\Component\HttpFoundation\Response;


class AdminPostController extends Controller
{
    /**
     * @Route("/admin-post", name="admin_post_list")
     */
    public function listAction()
    {
        $posts = $this->getDoctrine()
            ->getRepository('AppBundle:Post')
            ->findBy(array(),array('created_time'=>'desc'));

        return $this->render('adminPost/list.html.twig',array(
            'posts'=>$posts,
        ));
    }

    /**
     * @Route("/admin-post/new", name="admin_post_new")
     */
    public function newAction(Request $request)
    {
        $post = new Post();

        $form = $this->createFormBuilder($post)
            ->add('title', 'Symfony\Component\Form\Extension\Core\Type\TextType')
            ->add('content', 'Symfony\Component\Form\Extension\Core\Type\TextareaType')
            ->add('is_published', 'Symfony\Component\Form\Extension\Core\Type\ChoiceType',array(
                'choices'  => array(
                    'Yes' => 1,
                    'No' => 0,
                ),
                'choices_as_values' => true,
            ))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $post = $form->getData();
            $user = $this->get('security.token_storage')->getToken()->getUser();
            $post->setUser($user);
            $post->setSeoUrl($this->createSeoUrl($post->getTitle()));
            $post->setCreatedTime(new \DateTime());
            $post->setUpdateTime(new \DateTime());
            $em = $this->getDoctrine()->getManager();
            $em->persist($post);
            $em->flush();

            return $this->redirectToRoute('admin_post_show',array('id' => $post->getId()));
        }

        $csrfToken = $this->container->get('form.csrf_provider')->generateCsrfToken('authenticate');

        return $this->render('adminPost/new.html.twig', array(
            'form' => $form->createView(),
            'csrf_token' => $csrfToken,
        ));
    }

    /**
     * @Route("/admin-post/show/{id}", name="admin_post_show", requirements={"id": "\d+"})
     */
    public function showAction($id)
    {
        $post = $this->getDoctrine()
            ->getRepository('AppBundle:Post')
            ->find($id);

        if (!$post) {
            throw $this->createNotFoundException('No post found for id '.$id);
        }

        return $this->render('adminPost/show.html.twig',array(
            'post'=>$post,
        ));
    }

    /**
     * @Route("/admin-post/update/{id}", name="admin_post_update", requirements={"id": "\d+"})
     */
    public function updateAction($id, Request $request)
    {
        $post = $this->getDoctrine()
            ->getRepository('AppBundle:Post')
            ->find($id);

        if (!$post) {
            throw $this->createNotFoundException('No post found for id '.$id);
        }

        $form = $this->createFormBuilder($post)
            ->add('title', 'Symfony\Component\Form\Extension\Core\Type\TextType')
            ->add('content', 'Symfony\Component\Form\Extension\Core\Type\TextareaType')
            ->add('is_published', 'Symfony\Component\Form\Extension\Core\Type\ChoiceType',array(
                'choices'  => array(
                    'Yes' => 1,
                    'No' => 0,
                ),
                'choices_as_values' => true,
            ))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $post = $form->getData();
            $user = $this->get('security.token_storage')->getToken()->getUser();
            $post->setUser($user);
            $post->setUpdateTime(new \DateTime());
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            return $this->redirectToRoute('admin_post_show',array('id' => $post->getId()));
        }

        $csrfToken = $this->container->get('form.csrf_provider')->generateCsrfToken('authenticate');

        return $this->render('adminPost/update.html.twig', array(
            'form' => $form->createView(),
            'csrf_token' => $csrfToken,
        ));
    }

    /**
     * @Route("/admin-post/delete/{id}", name="admin_post_delete", requirements={"id": "\d+"})
     * @Method({"POST"})
     */
    public function deleteAction($id)
    {
        $post = $this->getDoctrine()
            ->getRepository('AppBundle:Post')
            ->find($id);

        if (!$post) {
            throw $this->createNotFoundException('No post found for id '.$id);
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($post);
        $em->flush();

        $serializedEntity = $this->container->get('serializer')->serialize(array('result'=>true), 'json');
        return new Response($serializedEntity);
    }

    private function createSeoUrl($text)
    {
        $seoUrl = $this->convertToSeoUrl($text);

        $repository = $this->getDoctrine()
            ->getRepository('AppBundle:Post');

        $count = $repository->createQueryBuilder('p')
            ->andWhere('p.seo_url = :seoUrl')
            ->setParameter('seoUrl', $seoUrl)
            ->select('COUNT(p.id)')
            ->getQuery()
            ->getSingleScalarResult();

        if ($count>0) {
            $seoUrl .= '-'.($count+1);
        }

        return $seoUrl;
    }

    private function convertToSeoUrl($text)
    {
        $url = preg_replace('~[^\\pL0-9_]+~u', '-', $text);
        $url = trim($url, "-");
        $url = iconv("utf-8", "us-ascii//TRANSLIT", $url);
        $url = strtolower($url);
        $url = preg_replace('~[^-a-z0-9_]+~', '', $url);
        return $url;
    }
}
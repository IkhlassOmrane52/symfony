<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Categories;

class ArticlesController extends AbstractController
{
    /**
     * @Route("/articles", name="articles")
     */
    public function index()
    { 
        $articles=$this->getDoctrine()->getRepository(Categories::class)->findBy([],['created_at' => 'desc']);
        return $this->render('articles/index.html.twig', 
        /*[
            'artices' => $articles,
        ]*/
         compact('articles'));
     }
}

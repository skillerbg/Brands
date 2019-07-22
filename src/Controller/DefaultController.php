<?php

namespace App\Controller;

use App\Entity\Brands;
use App\Entity\Reviews;
use App\Form\ReviewsType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{

    public function index(Request $request)
    {  
        $entityManager = $this->getDoctrine()->getManager();

        $em = $this->getDoctrine()->getManager();
       $brand= $em->getRepository(Brands::class)->findByRating();
            return $this->render('index/index.html.twig', [
                'brand' => $brand
            ]);
    }
}

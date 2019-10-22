<?php

namespace App\Controller;

use App\Entity\Brands;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends AbstractController
{

    public function index(Request $request)
    {
        //Get top8 brands to be displayed on the index page
        $entityManager = $this->getDoctrine()->getManager();
        $em = $this->getDoctrine()->getManager();
        $brand = $em->getRepository(Brands::class)->findByRating();

        return $this->render('index/index.html.twig', [
            'brand' => $brand,
        ]);
    }
}

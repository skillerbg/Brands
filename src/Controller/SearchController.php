<?php

namespace App\Controller;

use App\Entity\Brands;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SearchController extends AbstractController
{
    /**
     * @param Request $request
     *
     * @Route("/search_field", name="search_field")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function create(Request $request)
    {
        return $this->render('index/navBar/search_field.html.twig');

    }
    /**
     * @param Request $request
     * @Route("raw/search", name="searching")
     *

     */

    public function search(Request $request) //search the Db for brand entities with the ajax params

    {

        $entityManager = $this->getDoctrine()->getManager();
        $ajaxQuery = $request->request->get('query');

        $result = $entityManager->getRepository(Brands::class)->createQueryBuilder('o')
            ->where('o.brand_name LIKE :n')
            ->setParameter('n', '%' . $ajaxQuery . '%')
            ->getQuery()
            ->getArrayResult();
        return $this->json(array($result));
    }

}

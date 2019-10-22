<?php

namespace App\Controller;

use App\Entity\Brands;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BrowseController extends AbstractController
{

/**
 * @param Request $request
 * @Route("/search", name="search")
 *  @return Response
 */
    public function search(Request $request, PaginatorInterface $paginator)
    {
        $entityManager = $this->getDoctrine()->getManager();
        //Get the brand entities ranked by the given filter
        $repository = $entityManager
            ->getRepository(Brands::class);
        $result = $repository->browseBrands($request);

        //Sort the result in pages
        $pagination = $paginator->paginate(
            $result,
            $request->query->getInt('page', 1), /*page number*/
            10/*limit per page*/
        );

        return $this->render('browse/search_result.html.twig', ['brand' => $pagination]);

    }

    /**
     * @param Request $request
     * @Route("/search_page", name="searchPage")
     *  @return Response
     */
    public function searchPage(Request $request)
    {
        return $this->render('browse/search.html.twig', ['request' => $request]);

    }

}

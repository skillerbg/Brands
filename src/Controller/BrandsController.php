<?php

namespace App\Controller;

use App\Entity\Brands;
use App\Entity\Reviews;
use App\Form\BrandsType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BrandsController extends AbstractController
{

    /**
     * @param Request $request
     * @Route("/brands/create", name="brands-create")
     * @IsGranted("ROLE_USER")
     *  @return Response
     */
    public function createBrand(Request $request)
    {

        $brand = new Brands();
        $form = $this->createForm(BrandsType::class, $brand);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $task = $form->getData();
            $task->setUserId($this->getUser());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($task);
            $entityManager->flush();

            return $this->redirectToRoute('brand_show', array('slug' => $task->getID()));
        }
        return $this->render('brands/newBrand.html.twig', [
            'form' => $form->createView(),
        ]);

    }
    /**
     * @IsGranted("ROLE_USER")
     */
    public function show($slug)
    {

        $brand = $this->getDoctrine()
            ->getRepository(Brands::class)
            ->find($slug);

        if (!$brand) {
            throw $this->createNotFoundException(
                'No product found for id ' . $slug
            );
        }
        $reviews = $this->getDoctrine()
            ->getRepository(Reviews::class)
            ->findBy(
                array('brand_id' => $slug),
                array('date' => 'DESC')
            );
        return $this->render('brands/displayBrand.html.twig', ['brand' => $brand, 'reviews' => $reviews]);
    }
    /**
     * @Route("/category-select", name="category_select")
     */
    public function getSpecificCategorySelect(Request $request)
    {
        $brand = new Brands();
        $brand->setCategory($request->query->get('Category'));
        $form = $this->createForm(BrandsType::class, $brand);
        if (!$form->has('SubCategory')) {
            return new Response(null, 204);
        }
        return $this->render('brands/category1.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}

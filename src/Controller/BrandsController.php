<?php

namespace App\Controller;

use App\Entity\Brands;
use App\Entity\Reviews;
use App\Form\BrandsType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class BrandsController extends AbstractController
{

    /**
     * @param Request $request
     * @Route("/brands/create", name="brands-create")
     *  @return Response
     */
    public function createBrand(Request $request)
    {
        $brand = new Brands();
        $form = $this->createForm(BrandsType::class, $brand);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // $form->getData() holds the submitted values
            // but, the original `$task` variable has also been updated
            $task = $form->getData();
            $task->setUserId($this->getUser());
            // ... perform some action, such as saving the task to the database
            // for example, if Task is a Doctrine entity, save it!
                $entityManager = $this->getDoctrine()->getManager();
             $entityManager->persist($task);
             $entityManager->flush();

            return $this->redirectToRoute('brands-create');
        }
            return $this->render('brands/newBrand.html.twig', [
                'form' => $form->createView(),
            ]);

    }


    public function show($slug)
    {
        $url = $this->generateUrl(
            'brand_show',
            ['slug' => $slug]
        );
       $brand = $this->getDoctrine()
                     ->getRepository(Brands::class)
                     ->find($slug);

        if (!$brand) {
            throw $this->createNotFoundException(
                'No product found for id '.$slug
            );
        }
        $reviews=$this->getDoctrine()
            ->getRepository(Reviews::class)
            ->findBy(['brand_id'=>$slug]);
        return $this->render('brands/displayBrand.html.twig', ['brand' => $brand,'reviews' => $reviews]);
    }

    
}

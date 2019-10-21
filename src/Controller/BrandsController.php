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
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class BrandsController extends AbstractController
{

    /**
     * @param Request $request
     * @Route("/brands/create", name="brands-create")
     * @IsGranted("ROLE_USER")
     *  @return Response
     */
    public function createBrand(Request $request)
    {$str="aa";
        $arr=array(1,$str);
        $str2=$arr[1];
        var_dump($str2);
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


            return $this->redirectToRoute('brand_show',array('slug'=> $task->getID()));
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
            ->findBy(
             array('brand_id'=> $slug), 
             array('date' => 'DESC')
           );
        return $this->render('brands/displayBrand.html.twig', ['brand' => $brand,'reviews' => $reviews]);
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

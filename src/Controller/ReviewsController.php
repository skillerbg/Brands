<?php

namespace App\Controller;

use App\Entity\Brands;
use App\Entity\Reviews;
use App\Form\ReviewsType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ReviewsController extends AbstractController
{

    public function newReview(Request $request,$slug)
    {   $review=new Reviews();
        $form=$this->createForm(ReviewsType::class,$review);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // $form->getData() holds the submitted values
            // but, the original `$task` variable has also been updated
            $task = $form->getData();
            $task->setUserId($this->getUser());
            $entityManager = $this->getDoctrine()->getManager();
            $brand = $entityManager->getRepository(Brands::class)->find($slug);
            $task->setBrandID($brand);
            $task->setDate(new \DateTime());
            // ... perform some action, such as saving the task to the database
            // for example, if Task is a Doctrine entity, save it!

            $this->updateRating($slug,$task->getStars());
            $entityManager->persist($task);
            $entityManager->flush();
            

            return $this->redirectToRoute('create_review');
        }
        return $this->render('reviews/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    public function updateRating($brandId,$newStars){
        $entityManager = $this->getDoctrine()->getManager();
        $brand = $entityManager->getRepository(Brands::class)->find($brandId);
        $brand->setTotalReviews($brand->getTotalReviews()+1);
        $brand->setTotalStars($brand->getTotalStars()+$newStars);
        $brand->setRating();
        $entityManager->flush();

    }
}

<?php

namespace App\Controller;

use App\Entity\Brands;
use App\Entity\Reviews;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class ReviewsController extends AbstractController
{

    public function newReview(Request $request, $slug)
    {$entityManager = $this->getDoctrine()->getManager();

        $repository = $entityManager->getRepository(Reviews::class)->userHasReview($slug, $this->getUser());
        if (is_null($repository)) {
            $review = new Reviews();

            $params = $request->request->get('review');
            $review->setComment($params['comment']);
            $review->setStars($params['stars']);
            
            if ($params) {
                

                // $form->getData() holds the submitted values
                // but, the original `$task` variable has also been updated
                $review->setUserId($this->getUser());
                $brand = $entityManager->getRepository(Brands::class)->find($slug);
                $review->setBrandID($brand);
                $review->setDate(new \DateTime());
                // ... perform some action, such as saving the task to the database
                // for example, if Task is a Doctrine entity, save it!

                $this->updateRating($slug, $review->getStars());
                $entityManager->persist($review);
                $entityManager->flush();

                return $this->redirectToRoute('brand_show', array('slug' => $slug));
            }

            return $this->render('browse/reviews.html.twig', array('slug' => $slug));
        } else {
        
            return $this->render('brands/review.html.twig', ['review' => $repository]);

        }

    }
    public function updateRating($brandId, $newStars)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $brand = $entityManager->getRepository(Brands::class)->find($brandId);
        $brand->setTotalReviews($brand->getTotalReviews() + 1);

        $brand->setTotalStars($brand->getTotalStars() + $newStars);
        $brand->setRating();
        $entityManager->flush();

    }
}

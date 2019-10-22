<?php

namespace App\Controller;

use App\Entity\Brands;
use App\Entity\Reviews;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class ReviewsController extends AbstractController
{

    public function newReview(Request $request, $slug)
    {
        $entityManager = $this->getDoctrine()->getManager();
        //Check if the user has review on the selected brand
        $repository = $entityManager->getRepository(Reviews::class)->userHasReview($slug, $this->getUser());
        if (is_null($repository)) {

            $params = $request->request->get('review');
            $brand = $entityManager->getRepository(Brands::class)->find($slug);

            if ($params) {

                $review = new Reviews();
                $review->setComment($params['comment'])
                    ->setStars($params['stars'])
                    ->setUserId($this->getUser())
                    ->setBrandID($brand)
                    ->setDate(new \DateTime());

                //update the brand's rating
                $this->updateRating($brand, $review->getStars());

                $entityManager->persist($review);
                $entityManager->flush();

                return $this->redirectToRoute('brand_show', array('slug' => $slug));
            }

            return $this->render('browse/reviews.html.twig', array('slug' => $slug));
        } else {

            return $this->render('brands/review.html.twig', ['review' => $repository]);

        }

    }
    public function updateRating($brand, $newStars)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $brand->setTotalReviews($brand->getTotalReviews() + 1)
            ->setTotalStars($brand->getTotalStars() + $newStars)
            ->setRating();
        $entityManager->flush();

    }
}

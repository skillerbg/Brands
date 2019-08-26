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
            return $this->render('brands/index.html.twig', [
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
        return $this->render('brands/show.html.twig', ['brand' => $brand,'reviews' => $reviews]);
    }

    /**
     * @param Request $request
     * @Route("/brands", name="brands")
     *  @return Response
     */
    public function list(Request $request)
    {
        $brand = new Brands();

        return $this->render('brands/index.html.twig');

    }
    /**
     * @param Request $request
     * @Route("/search", name="search")
     *  @return Response
     */
    public function search(Request $request){
        $sql1="SELECT b.id as Brand_id, b.brand_name as Brand_name, b.logo as logo, r.id as review_id, r.`comment` as review_comment ,r.stars as stars ,
        SUM(stars) as total_stars, 
        COUNT(*) as total_reviews, 
        cast(SUM(stars)/COUNT(*) AS DECIMAL(5,1))
         AS average_rating
        FROM brands b
            INNER JOIN reviews r ON b.id = r.brand_id_id
            INNER JOIN fos_user u ON r.user_id_id =u.id
       
        ";
        $sql2="";
            if( $filter=$request->query->get('filter')){
                if(! count( array_filter( $filter)) == 0) {
                    $sql2="WHERE ";
                    if($filter["gender"]=="f"){
                        if(strlen($sql2)>10){$sql2=$sql2." AND ";}
                        $sql2=$sql2."gender = 'f'";
                    }
                    if($filter["gender"]=="m"){
                        if(strlen($sql2)>10){$sql2=$sql2." AND ";}
                        $sql2=$sql2."gender = 'm'";
                    }
                    if($filter["minAge"]){
                        $minAge=intval($filter["minAge"]);
                        
                        if(strlen($sql2)>10){$sql2=$sql2." AND ";}
                        $sql2=$sql2."age > '{$minAge}'";
                    }
                    if($filter["maxAge"]){
                        $maxAge=intval($filter["maxAge"]);

                        if(strlen($sql2)>10){$sql2=$sql2." AND ";}
                        $sql2=$sql2."age < '{$maxAge}'";
                    }

                   
                };
        }
    
  
    $sql3="
    GROUP BY b.id
    ORDER BY average_rating DESC;
    ";
    $sql=$sql1.$sql2.$sql3;
$em = $this->getDoctrine()->getManager();
$conn = $em->getConnection();
$stmt = $conn->prepare($sql);
$stmt->execute();

$result=$stmt->fetchAll();


    return $this->render('search_result.html.twig', ['brand' => $result]);

    }

     /**
     * @param Request $request
     * @Route("/search_page", name="searchPage")
     *  @return Response
     */
    public function searchPage(Request $request){
        return $this->render('search.html.twig',['request' => $request]);

    }


    /**
     * @param Request $request
     * @Route("/brands/topTen", name="topTen")
     *  @return Response
     */
    public function topTen(Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();

        $em = $this->getDoctrine()->getManager();
       $brand= $em->getRepository(Brands::class)->findByRating();
            return $this->render('brands/topTen.html.twig', [
                'brand' => $brand
            ]);

  
}
}

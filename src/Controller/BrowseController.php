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

class BrowseController extends AbstractController
{

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
                if( $keyWords=$filter["keyWords"]){                       
                     $sql2=$sql2." Brand_name LIKE '%".$keyWords."%'";            
                    };
                    $filterOption="gender";
                    $andOr=')AND(';
                    
                    if(in_array("f", $filter[$filterOption])){
                        if(strlen($sql2)>10){$sql2=$sql2." ".$andOr." ";}
                        $andOr="OR";
                        $sql2=$sql2.$filterOption." = 'f'";
                    }
                    if(in_array("m", $filter[$filterOption])){
                        if(strlen($sql2)>10){$sql2=$sql2." ".$andOr." ";}
                        $andOr="OR";
                        $sql2=$sql2.$filterOption." = 'm'";
                    }
                    $filterOption="household";
                    $andOr=')AND(';
                    if(in_array("single", $filter[$filterOption])){
                       if(strlen($sql2)>10){$sql2=$sql2." ".$andOr." ";}
                        $andOr="OR";
                        $sql2=$sql2.$filterOption."= 'single'";
                    }
                    if(in_array("maried", $filter[$filterOption])){
                       if(strlen($sql2)>10){$sql2=$sql2." ".$andOr." ";}
                        $andOr="OR";
                        $sql2=$sql2.$filterOption." = 'maried'";
                    }
                    if(in_array("widowed", $filter[$filterOption])){
                       if(strlen($sql2)>10){$sql2=$sql2." ".$andOr." ";}
                        $andOr="OR";
                        $sql2=$sql2.$filterOption." = 'widowed'";
                    }
                    if(in_array("divorced", $filter[$filterOption])){
                       if(strlen($sql2)>10){$sql2=$sql2." ".$andOr." ";}
                        $andOr="OR";
                        $sql2=$sql2.$filterOption." = 'divorced'";
                    }
                    if(in_array("separated", $filter[$filterOption])){
                       if(strlen($sql2)>10){$sql2=$sql2." ".$andOr." ";}
                        $andOr="OR";
                        $sql2=$sql2.$filterOption." = 'separated'";
                    }
                    $filterOption="employment";
                    $andOr=')AND(';
                    if(in_array("employed", $filter[$filterOption])){
                       if(strlen($sql2)>10){$sql2=$sql2." ".$andOr." ";}
                        $andOr="OR";
                        $sql2=$sql2.$filterOption." = 'employed'";
                    }
                    if(in_array("self", $filter[$filterOption])){
                       if(strlen($sql2)>10){$sql2=$sql2." ".$andOr." ";}
                        $andOr="OR";
                        $sql2=$sql2.$filterOption." = 'self'";
                    }
                    if(in_array("unemployed", $filter[$filterOption])){
                       if(strlen($sql2)>10){$sql2=$sql2." ".$andOr." ";}
                        $andOr="OR";
                        $sql2=$sql2.$filterOption." = 'unemployed'";
                    }
                    if(in_array("student", $filter[$filterOption])){
                       if(strlen($sql2)>10){$sql2=$sql2." ".$andOr." ";}
                        $andOr="OR";
                        $sql2=$sql2.$filterOption." = 'student'";
                    }
                    if(in_array("retired", $filter[$filterOption])){
                       if(strlen($sql2)>10){$sql2=$sql2." ".$andOr." ";}
                        $andOr="OR";
                        $sql2=$sql2.$filterOption." = 'retired'";
                    }
                    if(in_array("unable", $filter[$filterOption])){
                       if(strlen($sql2)>10){$sql2=$sql2." ".$andOr." ";}
                        $andOr="OR";
                        $sql2=$sql2.$filterOption." = 'unable'";
                    }

                    $filterOption="education";
                    $andOr=')AND(';
                    
                    if(in_array("middle", $filter[$filterOption])){
                       if(strlen($sql2)>10){$sql2=$sql2." ".$andOr." ";}
                        $andOr="OR";
                        $sql2=$sql2.$filterOption." = 'middle'";
                    }
                    if(in_array("high", $filter[$filterOption])){
                       if(strlen($sql2)>10){$sql2=$sql2." ".$andOr." ";}
                        $andOr="OR";
                        $sql2=$sql2.$filterOption." = 'high'";
                    }
                    if(in_array("college", $filter[$filterOption])){
                       if(strlen($sql2)>10){$sql2=$sql2." ".$andOr." ";}
                        $andOr="OR";
                        $sql2=$sql2.$filterOption." = 'college'";
                    }
                    if(in_array("bachelor", $filter[$filterOption])){
                       if(strlen($sql2)>10){$sql2=$sql2." ".$andOr." ";}
                        $andOr="OR";
                        $sql2=$sql2.$filterOption." = 'bachelor'";
                    }
                    if(in_array("master", $filter[$filterOption])){
                       if(strlen($sql2)>10){$sql2=$sql2." ".$andOr." ";}
                        $andOr="OR";
                        $sql2=$sql2.$filterOption." = 'master'";
                    }
                    if(in_array("pro", $filter[$filterOption])){
                       if(strlen($sql2)>10){$sql2=$sql2." ".$andOr." ";}
                        $andOr="OR";
                        $sql2=$sql2.$filterOption." = 'pro'";
                    }
                    if(in_array("doc", $filter[$filterOption])){
                       if(strlen($sql2)>10){$sql2=$sql2." ".$andOr." ";}
                        $andOr="OR";
                        $sql2=$sql2.$filterOption." = 'doc'";
                    }

                    if($filter["minAge"]){
                        $minAge=intval($filter["minAge"]);
                        
                    if(strlen($sql2)>10){$sql2=$sql2." )AND( ";}
                        $sql2=$sql2."age > '{$minAge}'";
                    }
                    if($filter["maxAge"]){
                        $maxAge=intval($filter["maxAge"]);

                    if(strlen($sql2)>10){$sql2=$sql2." )AND( ";}
                        $sql2=$sql2."age < '{$maxAge}'";
                    }

                   
                };
        }
      

        if(strlen($sql2)>10){
           
            $sql2 = substr_replace($sql2, "(", 5, 0);
                
           $sql2=$sql2.")";
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


    return $this->render('browse/search_result.html.twig', ['brand' => $result]);

    }

     /**
     * @param Request $request
     * @Route("/search_page", name="searchPage")
     *  @return Response
     */
    public function searchPage(Request $request){
        return $this->render('browse/search.html.twig',['request' => $request]);

    }


}
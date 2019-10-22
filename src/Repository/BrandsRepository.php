<?php

namespace App\Repository;

use App\Entity\Brands;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Brands|null find($id, $lockMode = null, $lockVersion = null)
 * @method Brands|null findOneBy(array $criteria, array $orderBy = null)
 * @method Brands[]    findAll()
 * @method Brands[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BrandsRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Brands::class);
    }

    /**
     * @return Brands[] Returns an array of Brands objects
     */

    public function findByRating()
    {
        return $this->createQueryBuilder('b')
            ->orderBy('b.rating', 'DESC')
            ->setMaxResults(9)
            ->getQuery()
            ->getResult()
        ;
    }

    /*
    public function findOneBySomeField($value): ?Brands
    {
    return $this->createQueryBuilder('b')
    ->andWhere('b.exampleField = :val')
    ->setParameter('val', $value)
    ->getQuery()
    ->getOneOrNullResult()
    ;
    }
     */
    public function browseBrands($request)
    {
        //String used as 'WHERE' clause for the sql query
        $sql = "";

        //check if the filter is set
        if ($filter = $request->query->get('filter')) {
            //check if options have been selected in the filter
            if (!count(array_filter($filter)) == 0) {

                $sql = "WHERE ";
                //Check if the user is searching for brands by name
                if ($keyWords = $filter["keyWords"]) {
                    $sql = $sql . " Brand_name LIKE '%" . $keyWords . "%'";
                };

                $filterOption = "gender"; //First filter option we'll be checking if the user has chosen
                $andOr = ')AND('; //'AND' or 'OR' string to be placed in the query
                $arr = array($filter, $filterOption, $sql, $andOr);//create an array to be used in a function

                //Loop through the filter options
                foreach (array('gender' => array('f', 'm'),
                    'household' => array('single', 'maried', 'widowed', 'divorced', 'separated'),
                    'employment' => array('employed', 'self', 'unemployed', 'student', 'retired', 'unable'),
                    'education' => array('middle', 'high', 'college', 'bachelor', 'master', 'pro', 'doc'),
                ) as $key => $filterOption2) {
                    $arr[1] = $key;
                    $arr[3] = ')AND(';
                    foreach ($filterOption2 as $filterVariable) {
                        //check if the filter option is selected and update the WHERE clause string
                        $arr = $this->matchFilterOption($filterVariable, $arr);
                    }
                }
                //Adds the min and max age to the query
                $sql = $arr[2];
                if ($filter["minAge"]) {
                    $minAge = intval($filter["minAge"]);
                    if (strlen($sql) > 10) {$sql = $sql . " )AND( ";}
                    $sql = $sql . "age > '{$minAge}'";
                }
                if ($filter["maxAge"]) {
                    $maxAge = intval($filter["maxAge"]);
                    if (strlen($sql) > 10) {$sql = $sql . " )AND( ";}
                    $sql = $sql . "age < '{$maxAge}'";
                }
            };
        }

        //Replace the '(' added by the last filter option with ')'
        if (strlen($sql) > 10) {
            $sql = substr_replace($sql, "(", 5, 0);
            $sql = $sql . ")";
        }

        //Add the WHERE clause we created to the rest of the query
        $sql =
            "SELECT b.id as Brand_id, b.brand_name as Brand_name, b.logo as logo, r.id as review_id, r.`comment` as review_comment ,r.stars as stars ,
        SUM(stars) as total_stars,
        COUNT(*) as total_reviews,
        cast(SUM(stars)/COUNT(*) AS DECIMAL(5,1))
         AS average_rating
        FROM brands b
            INNER JOIN reviews r ON b.id = r.brand_id_id
            INNER JOIN fos_user u ON r.user_id_id =u.id

        " . $sql . "
        GROUP BY b.id
        ORDER BY average_rating DESC;
        ";
        $conn = $this->getEntityManager()->getConnection();
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll();

        //return brand entities ranked by the chosen filter parameters
        return $result;

    }

    public function matchFilterOption($option, $arr)
    {$filterOption = $arr[1];
        $filter = $arr[0];

        if (in_array($option, $filter[$filterOption])) {
            $sql = $arr[2];
            if (strlen($arr[2]) > 10) {
                $sql = $sql . " " . $arr[3] . " ";
            }
            $arr[3] = "OR";
            $sql = $sql . $arr[1] . " = '" . $option . "'";
            $arr[2] = $sql;
        }
        return $arr;
    }
}

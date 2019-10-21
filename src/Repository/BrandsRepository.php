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
        $sql1 = "SELECT b.id as Brand_id, b.brand_name as Brand_name, b.logo as logo, r.id as review_id, r.`comment` as review_comment ,r.stars as stars ,
        SUM(stars) as total_stars,
        COUNT(*) as total_reviews,
        cast(SUM(stars)/COUNT(*) AS DECIMAL(5,1))
         AS average_rating
        FROM brands b
            INNER JOIN reviews r ON b.id = r.brand_id_id
            INNER JOIN fos_user u ON r.user_id_id =u.id

        ";
        $sql2 = "";

        if ($filter = $request->query->get('filter')) {
            if (!count(array_filter($filter)) == 0) {
                $sql2 = "WHERE ";

                if ($keyWords = $filter["keyWords"]) {
                    $sql2 = $sql2 . " Brand_name LIKE '%" . $keyWords . "%'";
                };
                $filterOption = "gender";
                $andOr = ')AND(';
                $arr = array($filter,$filterOption, $sql2,$andOr);
                $arr = $this->matchFilterOpetion('f', $arr );
                $arr = $this->matchFilterOpetion('m', $arr);

                $arr[1] = "household";
                $arr[3] = ')AND(';

                $arr = $this->matchFilterOpetion('single', $arr);
                $arr = $this->matchFilterOpetion('maried', $arr);
                $arr = $this->matchFilterOpetion('widowed', $arr);
                $arr = $this->matchFilterOpetion('divorced', $arr);
                $arr = $this->matchFilterOpetion('separated', $arr);

                $arr[1] = "employment";
                $arr[3] = ')AND(';

                $arr = $this->matchFilterOpetion('employed', $arr);

                $arr = $this->matchFilterOpetion('self', $arr);

                $arr = $this->matchFilterOpetion('unemployed', $arr);

                $arr = $this->matchFilterOpetion('student', $arr);

                $arr = $this->matchFilterOpetion('retired', $arr);

                $arr = $this->matchFilterOpetion('unable', $arr);

                $arr[1] = "education";
                $arr[3] = ')AND(';

                $arr = $this->matchFilterOpetion('middle', $arr);

                $arr = $this->matchFilterOpetion('high', $arr);

                $arr = $this->matchFilterOpetion('college', $arr);

                $arr = $this->matchFilterOpetion('bachelor', $arr);

                $arr = $this->matchFilterOpetion('master', $arr);

                $arr = $this->matchFilterOpetion('pro', $arr);

                $arr = $this->matchFilterOpetion('doc', $arr);
              $sql2=$arr[2];

                 
                if ($filter["minAge"]) {
                    $minAge = intval($filter["minAge"]);

                    if (strlen($sql2) > 10) {$sql2 = $sql2 . " )AND( ";}
                    $sql2 = $sql2 . "age > '{$minAge}'";
                }
                if ($filter["maxAge"]) {
                    $maxAge = intval($filter["maxAge"]);

                    if (strlen($sql2) > 10) {$sql2 = $sql2 . " )AND( ";}
                    $sql2 = $sql2 . "age < '{$maxAge}'";
                }

            };
        }

        if (strlen($sql2) > 10) {

            $sql2 = substr_replace($sql2, "(", 5, 0);

            $sql2 = $sql2 . ")";
        }

        $sql3 = "
    GROUP BY b.id
    ORDER BY average_rating DESC;
    ";
        $sql = $sql1 . $sql2 . $sql3;
        $conn = $this->getEntityManager()->getConnection();
        $stmt = $conn->prepare($sql);
        $stmt->execute();

        $result = $stmt->fetchAll();
        return $result;

    }
    public function matchFilterOpetion($option, $arr)
    {$filterOption = $arr[1];
        $filter = $arr[0];
     
        if (in_array($option,
            $filter[$filterOption]
        )) {
            $sql2=$arr[2];
            if (strlen($arr[2]) > 10) {
                $sql2 = $sql2 . " " . $arr[3] . " ";
            }
            $arr[3] = "OR";
            $sql2 = $sql2 . $arr[1] . " = '" . $option."'";
            $arr[2]=$sql2;
        }
        return $arr;
    }
}

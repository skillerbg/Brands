<?php

namespace App\Form;

use App\Entity\Brands;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;


class BrandsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('brand_name')
            ->add('logo')
            ->add('Category',ChoiceType::class, [
                'choices'  => [
                    'Transport' => "Transport",
                    'Mechanical_engineering' => "MechanicalEngineering",
                    'Foods and drinks' => 'FoodsAndDrinks',
                    'Trading' => "Trading",
                    'Media' => "Media",
                    'Fashion' => "Fashion",
                    'Telecommunications' => "Telecommunications",
                    'Security' => "Security",
                    'IT' => "IT",
                    'Finances' => "Finances",
                    'Cosmetics' => "Cosmetics",
                    'Agencies' => "Agencies",
                    'Electric Devices' => "ElectricDevices",

                ],
            ])
            ->add('SubCategory1',ChoiceType::class, [
                'choices'  => [
                    'Air Transport' => 'AirTransport',
                    'Railway' => 'RailwayTransport',
                    'Delivery Services' => 'DeliveryServices',
                    'Alcohol-free' => 'AlcoholFree',
                    'Beer' => 'Beer',
                    'Wine' => 'Wine',
                    'Water' => 'Water',
                    'Coffe' => 'Coffe',
                    'Meat' => 'Meat',
                    'Dairy' => 'Dairy',
                    'Packaged food' => 'PackagecFood',
                    'Liquor' => 'Liquor',
                    'Real Estate' => 'RealEstate',
                    'Laundry' => 'Laundry',
                    'Body Care' => 'BodyCare',
                    'Automobiles' => 'Automobiles',
                    'Press' => 'Press',
                    'Radio' => 'Radio',
                    'Television' => 'Televison',
                    'Mobile Operator' => 'MobileOperator',
                    'Home' => 'Home',
                    'Restaurants' => 'Restaurants',
                    'Library' => 'Library',
                    'Cosmetics' => 'Cosmetics',
                    'Hypermarkets' => 'Hypermarkets',
                    'Banks' => 'Banks',
                    'Insurance' => 'Insurance',
                    'Websites' => 'Websites',

                ],
                ])
            ->add('SubCategory2',ChoiceType::class, [
                'choices'  => [
                   
                            'Sugar' => 'Sugar',
                            'Nuts' => 'Nuts', 
                            
                            'Main' => 'Main',
                            'Cold Tea' => 'Coldtea', 
                             'Carbonated' => 'Carbonated',
                            'Juice' => 'Juice', 
                ],
                ])
          
            ->add('user_id',HiddenType::class)
            ->add('save', SubmitType::class)

        ;
    }

//    public function configureOptions(OptionsResolver $resolver)
//    {
//        $resolver->setDefaults([
//            'data_class' => Brands::class,
//        ]);
//    }
}

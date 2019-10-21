<?php

namespace App\Form;

use App\Entity\Brands;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BrandsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /** @var Brands|null $brands */
        $brands = $options['data'] ?? null;
        $isEdit = $brands && $brands->getId();
        $category = $brands ? $brands->getCategory() : null;

        $builder
            ->add('brand_name')
            ->add('logo')
            ->add('Category', ChoiceType::class, [
                'choices' => [
                    '' => 'Chose category',
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
            ->add('description', TextareaType::class,['required' => false])
            ->add('wepagelink',TextType::class,['label' => "Web page link", 
                'required' => false
            ])
            ->add('facebooklink',TextType::class,['label' => "Facebook link", 
            'required' => false])
            ->add('twitterlink',TextType::class,['label' => "Twitter link", 
            'required' => false])
            ->add('instagramlink',TextType::class,['label' => "Instagram link", 
            'required' => false])

              
            ->add('user_id', HiddenType::class)
            ->add('submit', SubmitType::class, [
                'label' => 'Add',
                'attr' => ['class' => 'btn btn btn-primary', 'type' => 'button', 'id' => 'button-id-signup'],
            ]);

        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event) {
                /** @var Brands|null $data */
                $data = $event->getData();
                if (!$data) {
                    return;
                }
                $this->setupSubCategoryField(
                    $event->getForm(),
                    $data->getCategory()
                );
            }
        );

        $builder->get('Category')->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) {
                $form = $event->getForm();
                $this->setupSubCategoryField(
                    $form->getParent(),
                    $form->getData()
                );
            }
        );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Brands::class,
            'include_published_at' => false,

        ]);
    }
    private function getCategoryNameChoices(string $category)
    {

        $transport = [
            'AirTransport',
            'RailwayTransport',
            'DeliveryServices',

        ];
        $media = [
            'Press',
            'Radio',
            'Televison',

        ];
        $foods = [
            'Alcohol-free' => 'AlcoholFree',
            'Beer' => 'Beer',
            'Wine' => 'Wine',
            'Water' => 'Water',
            'Coffe' => 'Coffe',
            'Meat' => 'Meat',
            'Dairy' => 'Dairy',
            'Packaged food' => 'PackagecFood',
            'Liquor' => 'Liquor',

        ];
        $trading = [
            'Home' => 'Home',
            'Restaurants' => 'Restaurants',
            'Library' => 'Library',
            'Cosmetics' => 'Cosmetics',
            'Hypermarkets' => 'Hypermarkets',
            'Petroleum',
            'Electronics',

        ];
        $endineering = [
            'Automobiles' => 'Automobiles',

        ];
        $tele = [
            'Mobile Operator' => 'MobileOperator',

        ];
        $finances = [
            'Banks' => 'Banks',
            'Insurance' => 'Insurance',

        ];
        $it = [
            'Websites' => 'Websites',

        ];
        $cosmetics = [
            'Body Care' => 'BodyCare',
            'Laundry' => 'Laundry',

        ];
        $agencies = [
            'Real Estate' => 'RealEstate',

        ];
        $categoryChoices = [
            'Transport' => array_combine($transport, $transport),
            'Media' => array_combine($media, $media),
            'FoodsAndDrinks' => array_combine($foods, $foods),
            'Trading' => array_combine($trading, $trading),
            'MechanicalEngineering' => array_combine($endineering, $endineering),
            'Telecommunications' => array_combine($tele, $tele),
            'Finances' => array_combine($finances, $finances),
            'IT' => array_combine($it, $it),
            'Cosmetics' => array_combine($cosmetics, $cosmetics),            
            'Agencies' => array_combine($agencies, $agencies),
        
        ];
        return $categoryChoices[$category] ?? null;
    }
    private function setupSubCategoryField(FormInterface $form, ?string $category)
    {

        if (null === $category) {
            $form->remove('SubCategory');
            return;
        }
        $choices = $this->getCategoryNameChoices($category);
        $form->add('SubCategory', ChoiceType::class, [
            'placeholder' => '',
            'choices' => $choices,
            'required' => false,
        ]);
    }
}

<?php

namespace App\Form;

use App\Entity\Location;
use App\Entity\Ville;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use function Webmozart\Assert\Tests\StaticAnalysis\null;

/**
 * Ajout de lieu
 *
 * Class LocationType
 * @package App\Form
 */
class LocationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('ville', EntityType::class, [
                'label' => 'Ville',
                'class' => Ville::class,
                'choice_label' => 'nom',

                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->orderBy('c.nom', 'ASC')
                        ;
                },
            ])
            ->add('zip',TextType::class, [
                'label' => 'Code postal'
            ])
           ->add('nom', null, ['label' => 'Nom du lieu'])
            ->add('rue', null, ['label' => 'Rue'])
            ->add('latitude',null, [
                'label' => 'Latitude',
            ])
            ->add('longitude',null, [
                'label' => 'Longitude',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Location::class,
        ]);
    }
}

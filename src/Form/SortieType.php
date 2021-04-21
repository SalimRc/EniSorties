<?php

namespace App\Form;

use App\Entity\Campus;
use App\Entity\Lieu;
use App\Entity\Location;
use App\Entity\Participant;
use App\Entity\Sortie;
use App\Entity\Ville;
use DateInterval;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use function Sodium\add;

class SortieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', TextType::class, [
                'label'=>'Nom de la sortie'
            ])
            ->add('dateHeureDebut', DateTimeType::class, [
                'label'=> 'Date et heure de sortie',
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'form-control'
                ],
                'required'      => true,
            ])
            ->add('dateLimiteInscription', DateType::class, [
                'label'=>'Date limite inscription',
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'form-control'
                ],
                'required'      => true,

            ])
            ->add('campus',EntityType::class, [
                'label' => 'Campus',
                'class' => Campus::class,
                'choice_label' => 'nom',
                'query_builder' => function(EntityRepository $repository) {
                    return $repository->createQueryBuilder('c')->orderBy('c.nom', 'ASC');
                }
            ])
            ->add('participant',TextType::class, [
                'label' => 'Participant',
                'required'      => false,
            ])
            ->add('nbInscriptionsMax', TextType::class, [
                'label'=> 'Nombre de places'

            ])
            ->add('duree')
            ->add('infosSortie', TextareaType::class, [
                'label'=> 'Description et infos'
            ])
            ->add('lieu',EntityType::class, [
                'label' => 'Lieu',
                'class'=>Lieu::class,
                'choice_label'=>'nom'
            ])
            ->add('location', LocationType::class,[
                'label'=>'Localisation'
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
        ]);
    }

}

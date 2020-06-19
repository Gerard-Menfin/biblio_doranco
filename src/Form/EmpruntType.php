<?php

namespace App\Form;

use App\Entity\Emprunt;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\Abonne, App\Entity\Livre;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class EmpruntType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('date_emprunt', DateType::class, [ 
                "label" => "Emprunté le : ",
                "widget" => "single_text"
            ])
            ->add('date_retour', DateType::class, [ 
                "label" => "Rendu le : ",
                "widget" => "single_text",
                "required" => false
            ])
            ->add('abonne', EntityType::class, [
                "class" => Abonne::class,
                "choice_label" => "prenom"
            ])
            ->add('livre', EntityType::class, [
                "class" => Livre::class,
                "choice_label" => function(Livre $lvr){
                    return $lvr->getTitre() . " - " . $lvr->getAuteur();
                }
            ])
            ->add("enregistrer", SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Emprunt::class,
        ]);
    }
}

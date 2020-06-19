<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, ["label" => "E-mail"])
            ->add('password', PasswordType::class, [
                "label" => "Mot de passe",
                "mapped" => false,
            ])
            ->add('roles', ChoiceType::class, [
                "multiple" => true,
                "choices" => [
                    "Utilisateur" => "ROLE_USER",
                    "Bibliothécaire" => "ROLE_BIBLIOTHECAIRE",
                    "Admin" => "ROLE_ADMIN",
                    "Développeur" => "ROLE_DEV"
                ],
                "label" => "Statuts",
                "expanded" => true
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}

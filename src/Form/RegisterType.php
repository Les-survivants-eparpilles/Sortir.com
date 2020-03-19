<?php

namespace App\Form;

use App\Entity\Participant;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegisterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', TextType::class, ['label' => 'Nom', 'attr' => ['placeholder' => "Votre nom"]] )
            ->add('prenom', TextType::class, ['label' => 'Prenom', 'attr' => ['placeholder' => "Votre prenom"]])
            ->add('telephone', TextType::class, ['label' => 'Telephone', 'attr' => ['placeholder' => "Votre telephone"]])
            ->add('mail', EmailType::class, ['label' => 'Email', 'attr' => ['placeholder' => "Votre Email"]])
            ->add('pseudo', TextType::class, ['label' => 'Pseudo', 'attr' => ['placeholder' => "Votre pseudo"]])
            ->add('photo', TextType::class, ['label' => 'Photo', 'attr' => ['placeholder' => "Votre photo"]])
            ->add('motDePasse', RepeatedType::class, [ 'type' => PasswordType::class,
								                                    'invalid_message' => "Les mots de passes ne sont pas identique!",
								                                    'first_options' => ['label' => 'Mot de passe'],
								                                    'second_options' => ['label'=>'Confirmation du mot de passe']
							                                         ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Participant::class,
        ]);
    }
}

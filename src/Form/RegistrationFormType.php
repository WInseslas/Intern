<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\EmailType;


class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(child: 'email', type: EmailType::class, options: [
                'attr' => [
                    'placeholder' => 'exemple@themodernapplication.com',
                    'class' => 'form-control form-control-lg'
                ],
                'label' => 'Email address',
                'constraints' => [
                    new Assert\Email(["message" => "Please enter email"])
                ]
            ])
            ->add(child: 'fullname', options: [
                'attr' => [
                    'placeholder' => 'The Modern Application Factory',
                    'class' => 'form-control form-control-lg',
                    'minlength' => '6',
                    'maxlength' => '255'
                ],
                'label' => 'Full name',
                'constraints' => [
                    new Assert\Length([
                        'min' => 2, 
                        'minMessage' => 'Your first and last name must have at least {{ limit }} characters',
                        'max' => 255
                    ]),
                    new Assert\NotBlank([
                        'message' => 'Please enter your first and last name'
                    ]),
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}

<?php

    namespace App\Form;

    use Symfony\Component\Form\AbstractType;
    use Symfony\Component\Form\FormBuilderInterface;
    use Symfony\Component\Validator\Constraints as Assert;
    use Symfony\Component\OptionsResolver\OptionsResolver;
    use Symfony\Component\Form\Extension\Core\Type\{PasswordType, RepeatedType};


    class ChangePasswordFormType extends AbstractType
    {
        public function buildForm(FormBuilderInterface $builder, array $options): void
        {
            $builder
                ->add('plainPassword', RepeatedType::class, [
                    // instead of being set onto the object directly,
                    // this is read and encoded in the controller
                    'type' => PasswordType::class,
                    'label' => false,
                    'invalid_message' => 'Passwords do not match.',
                    'mapped' => false,
                    'first_options' => [
                        'label' => 'New Password',
                    ],
                    'second_options' => [
                        'label' => 'Rewrite new password',
                    ],
                    'constraints' => [
                        new Assert\NotBlank([
                            'message' => 'Please enter a password',
                        ]),
                        new Assert\Length([
                            'min' => 6,
                            'minMessage' => 'Your password should be at least {{ limit }} characters',
                            // max length allowed by Symfony for security reasons
                            'max' => 4096,
                        ]),
                    ],
                ])
                ->add('password', PasswordType::class, [
                    'label' => "Enter your current password to confirm changes",
                ])
            ;
        }

        public function configureOptions(OptionsResolver $resolver): void
        {
            $resolver->setDefaults([]);
        }
    }

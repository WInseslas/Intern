<?php

    namespace App\Form;

    use App\Entity\User;
    use Symfony\Component\Form\AbstractType;
    use Vich\UploaderBundle\Form\Type\VichImageType;
    use Symfony\Component\Form\FormBuilderInterface;
    use Symfony\Component\OptionsResolver\OptionsResolver;
    use Symfony\Component\Form\Extension\Core\Type\PasswordType;


    class ProfileType extends AbstractType
    {
        public function buildForm(FormBuilderInterface $builder, array $options): void
        {
            $builder
                ->add(child: 'fullname', options: [
                    'label' => 'Full name',
                    'required' => false
                ])
                ->add('imageFile', VichImageType::class, [
                    'required' => false,
                    'allow_delete' => true,
                    'delete_label' => false,
                    'download_label' => false
                ])
                ->add('password', PasswordType::class, [
                    'label' => "Enter your password to confirm changes",
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

<?php

    namespace App\Form;

    use App\Entity\Template;
    use Symfony\Component\Form\AbstractType;
    use Symfony\Component\Form\FormBuilderInterface;
    use Symfony\Component\Validator\Constraints\File;
    use Symfony\Component\OptionsResolver\OptionsResolver;
    use Symfony\Component\Form\Extension\Core\Type\FileType;


    class TemplateType extends AbstractType
    {
        public function buildForm(FormBuilderInterface $builder, array $options): void
        {
            $builder
                ->add(child: 'wording')
                ->add(child: 'file', type: FileType::class, options: [
                    'constraints' => [
                        new File([
                            'mimeTypes' => [
                                'application/pdf',
                                'application/x-pdf',
                                'application/msword',
                            ],
                            'mimeTypesMessage' => 'Please upload a document \'PDF, Microsoft Word\' Valid',
                        ])
                    ]
                ])
                ->add(child: "coordinates", options: [
                    'attr' => [
                        'placeholder' => 'Enter different position. e.g ',
                    ]
                ])
            ;
        }

        public function configureOptions(OptionsResolver $resolver): void
        {
            $resolver->setDefaults([
                'data_class' => Template::class,
            ]);
        }
    }

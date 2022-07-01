<?php

    namespace App\Form;

    use App\Entity\User;
    use App\Entity\People;
    use App\Repository\UserRepository;
    use Symfony\Bridge\Doctrine\Form\Type\EntityType;
    use Symfony\Component\Validator\Constraints as Assert;
    use Symfony\Component\OptionsResolver\OptionsResolver;
    use Symfony\Component\Form\{FormBuilderInterface, AbstractType};
    use Symfony\Component\Form\Extension\Core\Type\{EmailType, FileType, DateType, ChoiceType, BirthdayType, TextareaType};


    class PeopleType extends AbstractType
    {
        public function buildForm(FormBuilderInterface $builder, array $options): void
        {
            $builder
                ->add('firstname', null, [
                    'attr' => [
                        'minlength' => '2',
                        'maxlength' => '100'
                    ],
                    'label' => "First name",
                    'constraints' => [
                        new Assert\Length(['min' => 2, 'max' => 100]),
                        new Assert\NotBlank()
                    ]
                ])
                ->add('lastname', null, [
                    'label' => "Last name",
                    'constraints' => [
                        new Assert\Length(['max' => 100])
                    ]
                ])
                ->add('dateofbirth', BirthdayType::class, [
                    'label' => "Date of birth",
                    'constraints' => [
                        new Assert\NotBlank()
                    ],
                    'widget' => 'single_text'
                ])
                ->add('sex', ChoiceType::class, [
                    'choices'  => [
                        'Women' => true,
                        'Men' => false,
                    ],
                ])
                ->add('email', EmailType::class, [
                    'attr' => [
                        'minlength' => '4',
                        'maxlength' => '255'
                    ],
                    'constraints' => [
                        new Assert\Length(['min' => 4, 'max' => 255]),
                        new Assert\Email(),
                    ]
                ])
                ->add(child:  'post', options: [
                    'constraints' => [
                        new Assert\NotBlank(),
                        new Assert\NotNull(),
                    ]
                ])
                ->add('topic', TextareaType::class, [
                    'required' => false,
                ])
                ->add('startdate', DateType::class, [
                    'constraints' => [
                        new Assert\NotBlank()
                    ],
                    'widget' => 'single_text',
                    'label' => 'Start date'
                ])
                ->add('enddate', DateType::class, [
                    'constraints' => [
                        new Assert\NotBlank()
                    ],
                    'widget' => 'single_text',
                    'label' => 'End date'
                ])
                ->add('result')
                ->add(child: 'school', options: [
                    'constraints' => [
                        new Assert\Length(['min' => 2])
                    ],
                ])
                ->add('level', ChoiceType::class, [
                    'choices'  => [
                        'Level 1' => 0,
                        'Level 2' => 1,
                        'Level 3' => 2,
                        'Level 4' => 3,
                        'Level 5' => 4,
                    ],
                ])
                ->add('report',FileType::class, [
                    'required' => false,
                ])
                ->add('internshipletter',FileType::class, [
                    'required' => false,
                    'label' => "Internship letter"
                ])
                ->add('otherfile',FileType::class, [
                    'required' => false,
                    'label' => "Other file"
                ])  
                ->add(child: 'domain')
                ->add(child: 'user', type: EntityType::class, options: [
                    'class' => User::class,
                    'query_builder' => function(UserRepository $userRepository){
                       return $userRepository->createQueryBuilder(alias: 'u')->orderBy(sort: 'u.fullname', order: 'ASC');
                    },
                    'choice_label' => 'fullname',
                    'label' => 'Framer',
                    'attr' => [
                        'class' => "js-example-basic-single"
                    ]
                ])
            ;
        }

        public function configureOptions(OptionsResolver $resolver): void
        {
            $resolver->setDefaults([
                'data_class' => People::class,
            ]);
        }
    }

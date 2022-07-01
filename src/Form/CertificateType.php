<?php
    namespace App\Form;

    use App\Entity\Template;
    use Symfony\Component\Form\AbstractType;
    use Symfony\Component\Form\FormBuilderInterface;
    use Symfony\Bridge\Doctrine\Form\Type\EntityType;
    use Symfony\Component\OptionsResolver\OptionsResolver;
    use App\Repository\{PeopleRepository, TemplateRepository};
    use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

    class CertificateType extends AbstractType
    {
        private PeopleRepository $peopleRepository;
        public function __construct(PeopleRepository $peopleRepository)
        {
            $this->peopleRepository = $peopleRepository;
        }

        public function buildForm(FormBuilderInterface $builder, array $options): void
        {
            $builder
                ->add(child: 'template', type: EntityType::class, options: [
                    'class' => Template::class,
                    'query_builder' => function(TemplateRepository $templateRepository){
                        return $templateRepository->createQueryBuilder('t')
                            ->orderBy(sort: 't.wording', order: 'ASC')
                        ;
                    },
                    'choice_label' => 'wording',
                    'attr' => [
                        'class' => "js-example-basic-single"
                    ]
                ])
                ->add(child: 'people', type: ChoiceType::class, options: [
                    'multiple' => true,
                    'choices' => $this->getChoices($this->peopleRepository->people(1)),
                    'attr' => [
                        'class' => "js-example-basic-single"
                    ]
                ])
            ;
        }

        public function configureOptions(OptionsResolver $resolver): void
        {
            $resolver->setDefaults([]);
        }

        /**
         * @method getChoices
         * @param  array $choices
         * @return null | Array
         */
        private function getChoices(?array $choices) : Array
        {       
            $output = [];
            foreach ($choices as $value) {
                $firstname = $value->getFirstname();
                $lastname = $value->getLastname() ? $value->getLastname() : '';
                $output[$firstname . " " . $lastname] = $value->getId();
            }
            return $output;
        }
    }

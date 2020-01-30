<?php

namespace App\Form\Type;

use App\Entity\Book;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BookType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('author', ChoiceType::class, [
                'choices'  => $options['choices'],
            ])
            ->add('title', TextType::class)
            ->add('release_date', TextType::class)
            ->add('isbn', TextType::class)
            ->add('format', TextType::class)
            ->add('number_of_pages', TextType::class)
            ->add('description', TextType::class)
            ->add('submit', SubmitType::class)
        ;
    }

    /**
     * For uses array choices with all authors
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Book::class,
            'choices' => []
        ]);
    }
}
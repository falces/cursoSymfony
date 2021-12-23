<?php

namespace App\Form\Type;

use App\Entity\Book;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BookFormType extends AbstractType
{
    public function buildForm( $builder, array $options): void
    {
        $builder->add('title', TextType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Book::class,
        ]);
    }

    /**
     * @description Evita el uso del formato nombre_formulario -> campos en el cuerpo de la petición
     *
     * @return strinn
     */
    public function getName(): string
    {
        return '';
    }

    /**
     * @description Evita el uso del formato nombre_formulario -> campos en el cuerpo de la petición cuando
     *              se trabaja con Twig
     *
     * @return string
     */
    public function getBlockPrefix(): string
    {
        return '';
    }
}
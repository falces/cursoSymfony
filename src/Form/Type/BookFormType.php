<?php

namespace App\Form\Type;

use App\Form\Model\BookDto;
use App\Form\Type\CategoryFormType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BookFormType extends AbstractType
{
    public function buildForm( $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class)
            ->add('base64Image', TextType::class)
            ->add('categories', CollectionType::class, [
                # El usuario puede añadir categorías aunque no existan al crear un libro
                'allow_add' => true,
                # El usuario puede quitar categorías
                'allow_delete' => true,
                # Especificamos la clase a la que se refiere
                'entry_type' => CategoryFormType::class
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => BookDto::class,
        ]);
    }

    /**
     * @description Evita el uso del formato nombre_formulario -> campos en el cuerpo de la petición
     *
     * @return string
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
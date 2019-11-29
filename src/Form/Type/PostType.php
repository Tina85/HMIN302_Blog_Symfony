<?php

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class PostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class)
            ->add('slug', TextType::class)
            ->add('content', TextareaType::class)
            ->add('published', DateType::class)
            ->add('updated', DateType::class)
            ->add('updated', DateType::class)
            ->add('display', CheckboxType::class, [
			    'label'    => 'hide',
			    'required' => false,
			]);

            ->add('publish', SubmitType::class)
        ;
    }
}
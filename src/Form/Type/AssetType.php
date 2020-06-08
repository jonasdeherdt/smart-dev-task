<?php

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;

class AssetType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'attr' => ['class'=> 'form-control']
                ])
            ->add('attachment', FileType::class, [
                'mapped' => false,
                'attr' => ['class'=> 'form-attachment']
            ])
            ->add('save', SubmitType::class, [
                'attr' => ['class'=> 'btn btn-sm btn-primary']
            ])
        ;
    }
}
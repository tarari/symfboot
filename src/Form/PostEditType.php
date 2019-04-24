<?php

namespace App\Form;

use App\Entity\Post;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use App\Form\Type\TagsInputType;

class PostEditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Title',
                'attr'=>[
                    'autofocus' => true,
                    'class'=>'form-control'
                ]
            ])
            ->add('author',TextType::class,[
                'label'=>'Author',
                'attr'=>['class'=>'form-control']
            ])
            ->add('contents', TextareaType::class, [
                'label' => 'Contents',
                'required'=>false,
                'attr'=>[
                    'id'=>'editor',
                    'class'=>'form-control tinymce'
                ]
            ])
            ->add('createdAt', DateTimeType::class, [
                'label' => 'Created at',
                'widget'=>'single_text',
                'attr'=>[
                    'class'=>'form-control js-datepicker '
                ]
            ])
            ->add('tags', TagsInputType::class, [
                'label' => 'Tags',

                'required' => false,
                'attr'=>[
                    'data-role'=>'tagsinput',
                    'class'=>'form-control'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
        ]);
    }
}

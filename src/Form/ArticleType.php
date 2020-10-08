<?php

namespace App\Form;

use App\Entity\Article;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\Common\Annotations\Annotation\Attribute;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'label'  => 'Titre de l\'article',
            ])
            ->add('description', TextType::class, [
                'label'  => 'Desription de l\'article',
            ])
            ->add('content', TextType::class, [
                'label'  => 'Contenu de l\'article',
            ])
            ->add('URL', UrlType::class, [
                'label'  => 'URL de l\'image',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}

<?php

namespace App\Form;

use App\Search\Search;
use App\Entity\Formation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchType extends AbstractType{
    
    public function buildForm (FormBuilderInterface $builder, array $options) 
    {
        $builder
        
            ->add('search_formation',EntityType::class, [
                'label'=>false,
                'class'=>Formation::class,
                'required'=>false,
                'multiple'=>true,
                'expanded'=>true
            ]);
            
    }

    public function configureOptions (OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class'=>Search::class,
            //'method'=>'GET',
            'csrf_protection'=>false
        ]);
    }

    public function getBlockPrefix()
    {
        return "";
    }
}
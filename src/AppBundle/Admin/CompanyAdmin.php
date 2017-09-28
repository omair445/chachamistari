<?php

namespace AppBundle\Admin;

use A2lix\TranslationFormBundle\Form\Type\TranslationsType;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

class CompanyAdmin extends AbstractAdmin
{
    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('id')
            ->add('lat')
            ->add('location')
            ->add('area')
            ->add('service')
            ->add('longitude')
            ->add('imageUrl')
            ->add('phone')
            ->add('instagram')
            ->add('facebook')
            ->add('website')
            ->add('email')
            ->add('created')
        ;
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('id')
            ->add('translations',null,array(
                'label' => 'Company Name'
            ))
//            ->add('lat',null,array(
//                'label' => 'Latitude'
//            ))
//            ->add('longitude',null,array(
//                'label' => 'Longitude'
//            ))
//            ->add('imageUrl')
            ->add('location')
            ->add('description')
            ->add('startTime')
            ->add('endTime')
            ->add('shopage')
            ->add('owner')
//            ->add('area')
            ->add('service')
//            ->add('phone')
//            ->add('instagram')
//            ->add('facebook')
//            ->add('website')
//            ->add('email')
            ->add('isActive',null,array(
                'editable' => true
            ))
            ->add('created')
            ->add('_action', null, array(
                'actions' => array(
                    'show' => array(),
                    'edit' => array(),
                    'delete' => array(),
                ),
            ))
        ;
    }

    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
//            ->add('id')
            ->add('location')
            ->add('area')
            ->add('service')
            ->add('translations', TranslationsType::class)
            ->add('lat',null,array(
                'label' => 'Latitude'
            ))
            ->add('longitude',null,array(
                'label' => 'Longitude'
            ))
            ->add('description')
            ->add('startTime', 'time', array(
    'attr' => array('class' => 'fixed-time')
))
            ->add('endTime', 'time', array(
    'attr' => array('class' => 'fixed-time')
))
            ->add('shopage')
            ->add('owner')
            ->add('imageUrl')
            ->add('phone')
            ->add('instagram')
            ->add('facebook')
            ->add('website')
            ->add('email')
            ->add('isActive')
//            ->add('created')
        ;
    }

    /**
     * @param ShowMapper $showMapper
     */
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('id')
            ->add('lat')
            ->add('longitude')
            ->add('imageUrl')
            ->add('phone')
            ->add('instagram')
            ->add('facebook')
            ->add('description')
            ->add('startTime')
            ->add('endTime')
            ->add('shopage')
            ->add('owner')
            ->add('website')
            ->add('email')
            ->add('created')
        ;
    }
}

<?php

namespace AppBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use A2lix\TranslationFormBundle\Form\Type\TranslationsType;
use Sonata\AdminBundle\Show\ShowMapper;

class AppUserAdmin extends AbstractAdmin
{
    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('id')
            ->add('email')
            ->add('mobileNumber')
//            ->add('status', 'choice', array(
//                'editable' => true,
//                'choices' => array(
//                    'disabled' => 'Disabled',
//                    'enabled' => 'Enabled ',
//                ),
//            ))
            ->add('userRole')

        ;
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('id')
            ->add('email')
            ->add('mobileNumber')
            ->add('status', null, array(
        'editable' => true,
//        'choices' => array(
//            'disabled' => 'Disabled',
//            'enabled' => 'Enabled ',
//        ),
            ))
            ->add('userRole')


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
            ->add('email')
            ->add('mobileNumber')
//            ->add('password')
            ->add('status')
            ->add('userRole')
            ->add('verification_code')
            ->add('translations', TranslationsType::class)
        ;
    }

    /**
     * @param ShowMapper $showMapper
     */
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('id')
            ->add('email')
            ->add('mobileNumber')
            ->add('status')
            ->add('verification_code')
            ->add('userRole')

        ;
    }
}

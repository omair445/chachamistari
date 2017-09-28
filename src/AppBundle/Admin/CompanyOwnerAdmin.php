<?php

namespace AppBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

class CompanyOwnerAdmin extends AbstractAdmin
{
    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('id')
            ->add('created')
            ->add('status')
            ->add('name')
            ->add('email')
            ->add('cnic')
            ->add('phone')
            ->add('age')
            ->add('homeTown')
        ;
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
//            ->add('id')
            ->add('name')
            ->add('created')
            ->add('status')

            ->add('email')
            ->add('cnic')
            ->add('phone')
            ->add('age')
            ->add('homeTown')
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
//            ->add('created')
            ->add('status')
            ->add('name')
            ->add('email')
            ->add('cnic')
            ->add('phone')
            ->add('age')
            ->add('homeTown')
        ;
    }

    /**
     * @param ShowMapper $showMapper
     */
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('id')
            ->add('created')
            ->add('status')
            ->add('name')
            ->add('email')
            ->add('cnic')
            ->add('phone')
            ->add('age')
            ->add('homeTown')
        ;
    }
}

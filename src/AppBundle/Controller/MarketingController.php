<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 9/25/17
 * Time: 11:03 PM
 */

namespace AppBundle\Controller;


use Symfony\Component\Routing\Annotation\Route;

class MarketingController extends DefaultController
{
    /**
     * @return string
     * @Route("/marketing/register/company")
     */
    public function registerCompany()
    {
        return $this->render(':company_register:form.html.twig');
    }

}
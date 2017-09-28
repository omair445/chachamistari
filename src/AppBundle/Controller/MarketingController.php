<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 9/25/17
 * Time: 11:03 PM
 */

namespace AppBundle\Controller;


use AppBundle\Entity\Company;
use AppBundle\Entity\CompanyOwner;
use AppBundle\Entity\CompanyTranslation;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MarketingController extends DefaultController
{
    /**
     * @return string
     * @Route("/marketing/register/company",name="register_company_custom")
     */
    public function registerCompany(Request $request)
    {
        if (!$request->isMethod('POST')) {
            $category = [];
            $services = $this->getDoctrine()->getRepository("AppBundle:Category")->findAll();
            foreach ($services as $service) {
                $category[] = [
                    'id' => $service->getCurrentTranslation()->getId(),
                    'name' => $service->getCurrentTranslation()->getCatHeading()
                ];
            }
            return $this->render(':company_register:form.html.twig', array(
                'categories' => $category
            ));
        } else {
            $em = $this->getDoctrine()->getManager();
            $name = $request->get('name');
            $phone = $request->get('number');
            $address = $request->get('loc_address');
            $lat = $request->get('lat');
            $long = $request->get('long');

            $description = $request->get('description',null);
            $openingTime = $request->get('openingTime',null);
            $closingTime = $request->get('closingTime');
            $facebookUrl = $request->get('faceBookUrl',null);
            $shopAge= $request->get('shopAge');
//            owner Data ahead
            $ownerName = $request->get('ownerName',null);
            $ownerPhone = $request->get('ownerPhone',null);
            $cnic = $request->get('cnic',null);
            $ownerEmail = $request->get('ownerEmail',null);
            $ownerHomeTown = $request->get('ownerHomeTown',null);
            $ownerAge = $request->get('ownerAge',null);
            $service = $this->getDoctrine()->getRepository('AppBundle:Category')->find($request->get('category'));

            $owner = new CompanyOwner();
            $owner->setName($ownerName);
            $owner->setEmail($ownerEmail);
            $owner->setAge($ownerAge);
            $owner->setCnic($cnic);
            $owner->setHomeTown($ownerHomeTown);
            $owner->setPhone($ownerPhone);
            $em->persist($owner);
            $em->flush();



            $company = new Company();
            $company->setPhone($phone);
            $company->setLat($lat);
            $company->setLongitude($long);
            $company->setService($service);
            $company->setIsActive(false);
            $company->setDescription($description);
            $openingTime = new \DateTime($openingTime);
            $closingTime = new \DateTime($closingTime);
            $company->setStartTime($openingTime);
            $company->setEndTime($closingTime);
            $company->setFacebook($facebookUrl);
            $company->setShopage($shopAge);
            $company->setOwner($owner);
            $em->persist($company);
            $em->flush();



            $id = $company->getId();
            $trans = new CompanyTranslation();
            $trans->setLocale('en');
            $trans->setAddress($address);
            $trans->setName($name);
            $trans->setTranslatable($company);
            $em->persist($trans);
            $em->flush();
            $response = array(
                'code' => 200,
                'msg' => 'Object Created with Success',
                'locale' => 'en',
            );
            return new Response(json_encode($response));


        }

    }

}
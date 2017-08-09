<?php
/**
 * Created by PhpStorm.
 * User: omair
 * Date: 8/8/2017
 * Time: 9:04 AM
 */

namespace AppBundle\Controller;

use AppBundle\Entity\CompanyFavourite;
use AppBundle\Entity\Review;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CompanyController extends DefaultController
{
    /**
     * @param Request $request
     * @param null $locationId
     * @param null $category
     * @param string $lang
     * @param null $token
     * @param null $areaId
     * @return Response
     * @Route("/webservice/company_list/{locationId}/{areaId}/{category}/{lang}/{token}")
     */
    public function getCompanyListing(Request $request, $locationId = null, $category = null, $lang = 'en', $token = null, $areaId = null)
    {
        $em = $this->getDoctrine()->getManager();
        $em->getFilters()->disable('oneLocale');
        $response = array();
        if ($locationId && $category && $lang && $token && $areaId) {
            $locationObject = $this->getDoctrine()->getRepository("AppBundle:Location")->find($locationId);
            $categoryObject = $this->getDoctrine()->getRepository("AppBundle:Category")->find($category);
            $areaObject = $this->getDoctrine()->getRepository("AppBundle:Area")->find($areaId);
            $userObject = $this->getUserByToken($token);
            if ($locationObject && $categoryObject && $areaObject) {
                if ($userObject) {
                    $companyObjects = $this->getDoctrine()->getRepository("AppBundle:Company")->findBy(array(
                        'location' => $locationObject,
                        'service' => $categoryObject,
                        'area' => $areaObject
                    ));
                    foreach ($companyObjects as $obj) {
                        $ratingObject = $this->getDoctrine()->getRepository("AppBundle:Review")->findOneByCompany($obj);
                        $fvtObject = $this->getDoctrine()->getRepository("AppBundle:CompanyFavourite")->findOneByCompany($obj);
                        $isFvt = false;
                        if ($fvtObject) {
                            $isFvt = true;
                        }
                        /**
                         * @var $ratingObject Review
                         */
                        $trans = $obj->getTranslations();
                        $output = $trans->get($lang);
                        if ($output) {
                            $response[] = array(
                                'c_id' => $obj->getId(),
                                'c_name' => $output->getName(),
                                'c_address' => $obj->getAddress(),
                                'rating' => $ratingObject->getPercentage(),
                                'c_lat' => $obj->getLat(),
                                'c_long' => $obj->getLongitude(),
                                'c_phone' => $obj->getPhone(),
                                'c_picture' => $obj->getImageUrl(),
                                'c_instagram' => $obj->getInstagram(),
                                'c_facebook' => $obj->getFacebook(),
                                'c_twitter' => $obj->getTwitter(),
                                'c_website' => $obj->getWebsite(),
                                'c_email' => $obj->getEmail(),
                                'c_person' => $obj->getPerson(),
                                'c_favorite' => (bool)$isFvt,
                            );
                        } else {
                            $response[] = array(
                                'c_id' => $obj->getId(),
                                'c_name' => $output->getName(),
                                'c_address' => $obj->getAddress(),
                                'rating' => $fvtObject->getPercentage(),
                                'c_lat' => $obj->getLat(),
                                'c_long' => $obj->getLongitude(),
                                'c_phone' => $obj->getPhone(),
                                'c_picture' => $obj->getImageUrl(),
                                'c_instagram' => $obj->getInstagram(),
                                'c_facebook' => $obj->getFacebook(),
                                'c_twitter' => $obj->getTwitter(),
                                'c_website' => $obj->getWebsite(),
                                'c_email' => $obj->getEmail(),
                                'c_person' => $obj->getPerson(),
                                'c_favorite' => (bool)$isFvt,
                            );
                        }
                    }
                    if ($response) {
                        return new Response(json_encode($this->companyListingResponse($lang, 0, $response), JSON_PRETTY_PRINT), 200);
                    } else {
                        return new Response(json_encode($this->companyListingResponse($lang, 1, $response), JSON_PRETTY_PRINT), 200);
                    }


                } else {
                    return new Response(json_encode($this->companyListingResponse($lang, 2, []), JSON_PRETTY_PRINT), 200);
                }
            } else {
                $arr = [
                    'data' => array(
                        'result' => 0,
                        'message' => 'Location or Category or Area id is Invalid .(Error Message for Developer only . Translation is not available for Developers Errors)',
                    )
                ];
                return new Response(json_encode($arr), 400);
            }
        } else {
            $arr = ['data' => array(
                'result' => 0,
                'message' => 'Parameters Missing .(Error Message for Developer only . Translation is not available for Developers Errors)',
            )];
            return new Response(json_encode($arr), 400);
        }
    }

    /**
     * @param Request $request
     * @Route("/webservice/save_to_favorite/{companyId}/{token}/{lang}")
     */
    public function fvtCompany(Request $request, $companyId = null, $token = null, $lang = 'en')
    {
        $em = $this->getDoctrine()->getManager();
        $em->getFilters()->disable('oneLocale');
        $em = $this->getDoctrine()->getManager();
        if ($companyId && $token && $lang) {
            $companyObject = $this->getDoctrine()->getRepository("AppBundle:Company")->find($companyId);
            if ($companyObject) {
                $userObject = $this->getUserByToken($token);
                if ($userObject) {
                    $fvtObject = $this->getDoctrine()->getRepository("AppBundle:CompanyFavourite")->findOneByCompany($companyObject);
                    if ($fvtObject) {
                        return new Response(json_encode($this->addToFvtResponse($lang, 2, []), JSON_PRETTY_PRINT), 200);
                    } else {
                        $fvtObject = new CompanyFavourite();
                        $fvtObject->setCompany($companyObject);
                        $fvtObject->setUser($userObject);
                        $fvtObject->setUpdated(new \DateTime('now'));
                        $em->persist($fvtObject);
                        $em->flush();
                        return new Response(json_encode($this->addToFvtResponse($lang, 0, []), JSON_PRETTY_PRINT), 200);
                    }
                } else {
                    ///////company listing response is called for session logout message only
                    return new Response(json_encode($this->companyListingResponse($lang, 2, []), JSON_PRETTY_PRINT), 400);
                }
            } else {
                $arr = [
                    'data' => array(
                        'result' => 0,
                        'message' => 'Company id is invalid .(Error Message for Developer only . Translation is not available for Developers Errors)',
                    )
                ];
                return new Response(json_encode($arr), 400);
            }
        } else {
            $arr = [
                'data' => array(
                    'result' => 0,
                    'message' => 'Parameters Missing .(Error Message for Developer only . Translation is not available for Developers Errors)',
                )
            ];
            return new Response(json_encode($arr), 400);
        }
    }

    /**
     * @param Request $request
     * @Route("/webservice/remove_favorite/{token}/{fvtId}/{lang}")
     */
    public function unfvtCompany(Request $request, $token = null, $fvtId = null, $lang = 'en')
    {
        $em = $this->getDoctrine()->getManager();
        $em->getFilters()->disable('oneLocale');
        if ($fvtId && $token && $lang) {
            $userObject = $this->getUserByToken($token);
            if ($userObject) {
                $fvtObject = $this->getDoctrine()->getRepository("AppBundle:CompanyFavourite")->find($fvtId);
                if ($fvtObject) {
                    $em->remove($fvtObject);
                    $em->flush();
                    return new Response(json_encode($this->unFvtResponse($lang, 0, []), JSON_PRETTY_PRINT), 200);
                } else {
                    $arr = [
                        'data' => array(
                            'result' => 0,
                            'message' => 'Invalid Fvt id .(Error Message for Developer only . Translation is not available for Developers Errors)',
                        )
                    ];
                    return new Response(json_encode($arr), 400);
                }
            } else {
                ///////company listing response is called for session logout message only
                return new Response(json_encode($this->companyListingResponse($lang, 2, []), JSON_PRETTY_PRINT), 400);
            }
        } else {
            $arr = [
                'data' => array(
                    'result' => 0,
                    'message' => 'Parameters Missing .(Error Message for Developer only . Translation is not available for Developers Errors)',
                )
            ];
            return new Response(json_encode($arr), 400);
        }
    }

    /**
     * @param Request $request
     * @Route("/webservice/get_favorite/{token}/{lang}")
     */
    public function getFvtCompanies(Request $request, $token = null, $lang = 'en')
    {
        $em = $this->getDoctrine()->getManager();
        $em->getFilters()->disable('oneLocale');
        if ($token) {
            $userObject = $this->getUserByToken($token);
            if ($userObject) {
                $companies = $this->getDoctrine()->getRepository("AppBundle:CompanyFavourite")->findBy(array(
                   'user' => $userObject
                ));
                $response = [];
                foreach ($companies as $company){
                    $obj = $this->getDoctrine()->getRepository("AppBundle:Company")->find($company->getCompany()->getId());
                    $ratingObject = $this->getDoctrine()->getRepository("AppBundle:Review")->findOneByCompany($obj);
                    $fvtObject = $this->getDoctrine()->getRepository("AppBundle:CompanyFavourite")->findOneByCompany($obj);
                    $isFvt = false;
                    if ($fvtObject) {
                        $isFvt = true;
                    }
                    /**
                     * @var $ratingObject Review
                     */
//                    dump($obj); die;
                    $trans = $obj->getTranslations();
                    $output = $trans->get($lang);
                    if ($output) {
                        $response[] = array(
                            'c_id' => $obj->getId(),
                            'c_name' => $output->getName(),
                            'c_address' => $obj->getAddress(),
                            'rating' => $ratingObject->getPercentage(),
                            'c_lat' => $obj->getLat(),
                            'c_long' => $obj->getLongitude(),
                            'c_phone' => $obj->getPhone(),
                            'c_picture' => $obj->getImageUrl(),
                            'c_instagram' => $obj->getInstagram(),
                            'c_facebook' => $obj->getFacebook(),
                            'c_twitter' => $obj->getTwitter(),
                            'c_website' => $obj->getWebsite(),
                            'c_email' => $obj->getEmail(),
                            'c_person' => $obj->getPerson(),
                            'c_favorite' => (bool)$isFvt,
                        );
                    } else {
                        $response[] = array(
                            'c_id' => $obj->getId(),
                            'c_name' => $output->getName(),
                            'c_address' => $obj->getAddress(),
                            'rating' => $fvtObject->getPercentage(),
                            'c_lat' => $obj->getLat(),
                            'c_long' => $obj->getLongitude(),
                            'c_phone' => $obj->getPhone(),
                            'c_picture' => $obj->getImageUrl(),
                            'c_instagram' => $obj->getInstagram(),
                            'c_facebook' => $obj->getFacebook(),
                            'c_twitter' => $obj->getTwitter(),
                            'c_website' => $obj->getWebsite(),
                            'c_email' => $obj->getEmail(),
                            'c_person' => $obj->getPerson(),
                            'c_favorite' => (bool)$isFvt,
                        );
                    }
                }
                if($response){
                    return new Response(json_encode($this->fvtCompaniesResponse($lang, 0, $response), JSON_PRETTY_PRINT), 200);
                }else{
                    return new Response(json_encode($this->fvtCompaniesResponse($lang, 1, $response), JSON_PRETTY_PRINT), 200);
                }

            } else {
                return new Response(json_encode($this->fvtCompaniesResponse($lang, 2, []), JSON_PRETTY_PRINT), 400);
            }
        } else {
            $arr = [
                'data' => array(
                    'result' => 0,
                    'message' => 'Parameters Missing .(Error Message for Developer only . Translation is not available for Developers Errors)',
                )
            ];
            return new Response(json_encode($arr), 400);
        }

    }

}
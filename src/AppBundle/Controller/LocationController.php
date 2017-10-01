<?php
/**
 * Created by PhpStorm.
 * User: omair
 * Date: 8/6/2017
 * Time: 8:33 AM
 */

namespace AppBundle\Controller;


use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LocationController extends DefaultController
{
    /**
     * @param Request $request
     * @Route("/webservice/locations/listing")
     */
    public function getLocationListing(Request $request)
    {
        $locale = $request->get('locale',NULL);
        $token = $request->get('token',NULL);
        $em = $this->getDoctrine()->getManager();
        $em->getFilters()->disable('oneLocale');
        $response = null;
        if ($token) {
            $user = $this->getUserByToken($token);
            if ($user) {
                $locations = $this->getDoctrine()->getRepository('AppBundle:Location')->findBy(array(
                    'isActive' => true
                ));
                foreach ($locations as $location) {
                    $trans = $location->getTranslations();
                    $output = $trans->get($locale);
//                    dump($output);die;
                    if ($output) {
                        $response[] = array(
                            'w_id' => $location->getId(),
                            'w_heading' => $output->getLocationHeading(),
                        );
                    } else {
                        $response[] = array(
                            'cat_id' => $location->getId(),
                            'w_heading' => '',
                        );
                    }
                }
                return new Response(json_encode($this->locationListingResponse($locale, 0, $response), JSON_PRETTY_PRINT), 200);
            } else {
                return new Response(json_encode($this->locationListingResponse($locale, 2, []), JSON_PRETTY_PRINT), 400);
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
     * @Route("/webservice/area/{locale}/{token}/{locationId}")
     */
    public function getAreaListing(Request $request,$locale = 'en',$token = null,$locationId = null)
    {
        $response = null;
        $em = $this->getDoctrine()->getManager();
        $em->getFilters()->disable('oneLocale');
        if($token && $locationId){
              $locationObject = $this->getDoctrine()->getRepository("AppBundle:Location")->find($locationId);
              if($locationObject){
                  $user = $this->getUserByToken($token);
                  if ($user) {
                      $areaObjects = $this->getDoctrine()->getRepository('AppBundle:Area')->findBy(array(
                          'location' => $locationObject,
                          'isActive' => true
                      ));
                      foreach ($areaObjects as $object) {
                          $trans = $object->getTranslations();
                          $output = $trans->get($locale);
//                    dump($output);die;
                          if ($output) {
                              $response[] = array(
                                  'area_id' => $object->getId(),
                                  'area_name' => $output->getAreaName(),
                                  'imagePath' => $object->getImagePath()
                              );
                          } else {
                              $response[] = array(
                                  'area_id' => $object->getId(),
                                  'area_name' => '',
                                  'imagePath' => $object->getImagePath()
                              );
                          }
                      }
                      if($response){
                          return new Response(json_encode($this->areaListingResponse($locale, 0, $response), JSON_PRETTY_PRINT), 200);
                      }else{
                          return new Response(json_encode($this->areaListingResponse($locale, 1, []), JSON_PRETTY_PRINT), 200);
                      }
                  }else{
                      return new Response(json_encode($this->areaListingResponse($locale, 2, []), JSON_PRETTY_PRINT), 400);
                  }

              }else{
                  $arr = [
                      'data' => array(
                          'result' => 0,
                          'message' => 'Location not found against location id.Please try with correct location id . (Error Message for Developer only . Translation is not available for Developers Errors)',
                      )
                  ];
                  return new Response(json_encode($arr), 400);
              }
        }else{
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
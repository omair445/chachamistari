<?php
/**
 * Created by PhpStorm.
 * User: omair
 * Date: 8/6/2017
 * Time: 6:22 AM
 */

namespace AppBundle\Controller;


use AppBundle\Entity\AppUser;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CategoryController extends DefaultController
{
    /**
     * @param Request $request
     * @Route("/webservice/get_khidmati_category_wiliya/{locale}/wiliya/{token}")
     */
    public function getCategoryListing(Request $request, $locale = 'en', $token = null)
    {
        $em = $this->getDoctrine()->getManager();
        $em->getFilters()->disable('oneLocale');
        $output = [];
        $response = [];
        if ($token) {
            $user = $this->getUserByToken($token);
            if ($user) {
                $categories = $this->getDoctrine()->getRepository('AppBundle:Category')->findBy(array(
                    'isActive' => true
                ));

                foreach ($categories as $category) {
                    $trans = $category->getTranslations();
                    $output = $trans->get($locale);
                    if($output){
                        $response[] = array(
                            'cat_id' => $category->getId(),
                            'cat_heading' => $output->getCatHeading(),
                            'cat_icon' => $category->getCatIconPath(),
                        );
                    }else{
                        $response[] = array(
                            'cat_id' => $category->getId(),
                            'cat_heading' => '',
                            'cat_icon' => $category->getCatIconPath(),
                        );
                    }


                }
                return new Response(json_encode($this->CategoryListingResponse($locale, 0,$response), JSON_PRETTY_PRINT), 200);




            } else {
                return new Response(json_encode($this->CategoryListingResponse($locale, 2,[]), JSON_PRETTY_PRINT), 400);
            }
        } else {
            $arr = [
                'data' => array(
                    'result' => 0,
                    'message' => 'Parameters Missing .(Error Message for Developer only . Translation is not available for Developers Errors)',
                )

            ];
            return new Response(json_encode($arr), 200);
        }
    }
}
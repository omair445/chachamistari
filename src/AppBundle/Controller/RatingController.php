<?php
/**
 * Created by PhpStorm.
 * User: omair
 * Date: 8/8/2017
 * Time: 5:18 AM
 */

namespace AppBundle\Controller;


use AppBundle\Entity\Review;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class RatingController extends DefaultController
{
    /**
     * @param Request $request
     * @Route("/webservice/save_to_rating/{companyId}/{token}/{rating}/{ip}/{lang}")
     */
    public function rateCompanyAction(Request $request, $companyId = null, $token = null, $rating = null, $ip = null, $lang = 'en')
    {
        $em = $this->getDoctrine()->getManager();
        if ($companyId && $token && $rating && $ip) {
            $userObject = $this->getUserByToken($token);
            if ($userObject) {
                $companyObject = $this->getDoctrine()->getRepository("AppBundle:Company")->find($companyId);
                if(!$companyObject){
                    $arr = [
                        'data' => array(
                            'result' => 0,
                            'message' => 'Invalid Company id .(Error Message for Developer only . Translation is not available for Developers Errors)',
                        )

                    ];
                    return new Response(json_encode($arr), 400);
                }
//                dump($companyObject );die;
                $ratingObject = $this->getDoctrine()->getRepository("AppBundle:Review")->findOneByCompany($companyObject);
//                dump($ratingObject );die;
                if ($ratingObject) {
                    /**
                     * @var $ratingObject Review
                     */
                    $ratingObject->setObtRating($this->jsonRating($rating, $ratingObject->getObtRating()));
                    $ratingObject->setPercentage(json_decode($this->percentageCalculation($ratingObject->getObtRating()), true));
                    $em->persist($ratingObject);
                    $em->flush();
                } else {
                    $ratingObject = new Review();
                    $ratingObject->setCompany($companyObject);
                    $ratingObject->setObtRating($this->jsonRating($rating, NULL));
                    $ratingObject->setPercentage($rating);
                    $em->persist($ratingObject);
                    $em->flush();
                }
                $response = array(
                    'ratingId' => $ratingObject->getId(),
                    'ratingPercentage' => $ratingObject->getPercentage()
                );

                return new Response(json_encode($this->ratingResponse($lang, 0, $response), JSON_PRETTY_PRINT), 200);

            } else {
                return new Response(json_encode($this->ratingResponse($lang, 2, []), JSON_PRETTY_PRINT), 400);
            }

        } else {
//            return new Response(json_encode($this->ratingResponse($lang, 0, $response), JSON_PRETTY_PRINT), 200);
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
     * @param $rating
     * @param $ratingArray
     * @return array
     */
    public function jsonRating($rating, $ratingArray)
    {
        if ($ratingArray) {
            if (!empty($ratingArray)) {
                $sizeOfArray = count($ratingArray);
                $ratingArray[++$sizeOfArray] = [
                    "rating" => $rating
                ];
                return $ratingArray;
            } else {
                $ratingResponseArray = [
                    "rating" => $rating
                ];
                return $ratingResponseArray;
            }

        } else {
            $ratingResponseArray[] = [
                "rating" => $rating
            ];
            return $ratingResponseArray;
        }
    }

    /**
     * @param $arrayReview
     * @return float|int
     */
    public function percentageCalculation($arrayReview)
    {
        $totalReviews = count($arrayReview) * 5;
        $obtainedReviews = 0;
        foreach ($arrayReview as $review) {
            $obtainedReviews = $obtainedReviews + $review['rating'];
        }
        return $obtainedReviews / $totalReviews * 5;
    }
}
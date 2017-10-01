<?php
/**
 * Created by PhpStorm.
 * User: omair
 * Date: 8/8/2017
 * Time: 9:04 AM
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Company;
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
     * @Route("/webservice/company_list")
     */
    public function getCompanyListing(Request $request)
    {
        $longitude = $request->get('longitude',null);
        $latitude = $request->get('latitude',null);
        $radius  = 20;   //miles
        $category = $request->get('category_id',null);
        $lang = $request->get('lang',null);
        $token = $request->get('token',null);

        $em = $this->getDoctrine()->getManager();
        $em->getFilters()->disable('oneLocale');
        $response = array();
        if ($longitude && $category && $lang && $token && $latitude) {

            $categoryObject = $category;
            $userObject = $this->getUserByToken($token);
            if ($categoryObject) {
                if ($userObject) {
                    $companyObjects = $this->getCompaniesByLongLat($longitude, $latitude, $radius);
                   $companiesByCategory = [];
                    foreach ($companyObjects as $obj) {
                       if($obj['cat_id'] == $categoryObject){
                           $companiesByCategory[] = $obj;
                       }
                    }

                    if ($companiesByCategory) {
                        return new Response(json_encode($this->companyListingResponse($lang, 0, $companiesByCategory), JSON_PRETTY_PRINT), 200);
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
     * @Route("/webservice/save_to_favorite")
     */
    public function fvtCompany(Request $request)
    {
        $companyId = $request->get('company_id',NULL);
        $token = $request->get('token',NULL);
        $lang = $request->get('lang','en');
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
     * @Route("/webservice/remove_favorite")
     */
    public function unfvtCompany(Request $request)
    {
        $token = $request->get('token',NULL);
        $fvtId = $request->get('fvt_id',NULL);
        $lang = $request->get('lang','en');

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
     * @Route("/webservice/favorite/companies")
     */
    public function getFvtCompanies(Request $request)
    {
        $token = $request->get('token',NULL);
        $lang = $request->get('lang','en');
        $em = $this->getDoctrine()->getManager();
        $em->getFilters()->disable('oneLocale');
        if ($token) {
            $userObject = $this->getUserByToken($token);
            if ($userObject) {
                $companies = $this->getDoctrine()->getRepository("AppBundle:CompanyFavourite")->findBy(array(
                    'user' => $userObject
                ));
                $response = [];
                foreach ($companies as $company) {
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
                if ($response) {
                    return new Response(json_encode($this->fvtCompaniesResponse($lang, 0, $response), JSON_PRETTY_PRINT), 200);
                } else {
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


    /**
     * @param Request $request
     * @Route("/image/upload",name="uploadImage")
     */
    public function uploadImage(Request $request)
    {
//        sleep(2);
        $object = $this->upload_image($request, 'company');
        $host = $request->getSchemeAndHttpHost();
        $basePath = null;
        if ($host == "http://localhost") {
            $basePath = $host . '/khidmat_group/web';
        } else {
            $basePath = $host;
        }
        if ($object[0] == 1) {
            $arr = array(
                'status' => 200,
                'msg' => 'image uploaded with success',
                'path' => $basePath . '/' . $object[1]
            );
        } else {
            $arr = array(
                'status' => 500,
                'msg' => 'Failed to upload Image ',
                'path' => null
            );
        }
        return new Response(json_encode($arr), 200);


    }


    protected function upload_image(Request $request, $image_type)
    {
        $path = "images/";

        switch ($image_type) {
            case "company":
                $path = "images/company/";
                break;


        }

        $filename = $_FILES["file"]["name"];
        $file_basename = substr($filename, 0, strripos($filename, '.')); // get file extention
        $file_ext = substr($filename, strripos($filename, '.')); // get file name
        $filesize = $_FILES["file"]["size"];
        $allowed_file_types = array('.png', '.jpg', '.jpeg', '.gif');

        if (in_array($file_ext, $allowed_file_types) && ($filesize < 2000000)) {
            // Rename file
            $currentTimeMicro = round(microtime(TRUE));
            $newfilename = md5($file_basename) . mt_rand(10000, 999999) . $currentTimeMicro . $file_ext;
            $newfilename2 = md5($file_basename) . mt_rand(10000, 999999) . $currentTimeMicro . $file_ext;
            $haveUrlofThumbnailofUploadImage = $this->makeThumbnail('file', $path, $newfilename, false, $path, 300, 300);
            $checkPath = $path . $newfilename;


            return [
                1,
                $path . $newfilename,
//                    $haveUrlofThumbnailofUploadImage
            ];


        } elseif (empty($file_basename)) {
            return [
                0,
                'please select file'
            ];
        } elseif ($filesize > 200000000) {
            return [
                0,
                'image is large'
            ];
        } else {
            return [
                0,
                'invalid type'
            ];
        }
    }

    /**
     * @param $field_name
     * @param $target_folder
     * @param $file_name
     * @param $thumb
     * @param $thumb_folder
     * @param $thumb_width
     * @param $thumb_height
     * @return bool|string
     */
    protected function makeThumbnail($field_name, $target_folder, $file_name, $thumb, $thumb_folder, $thumb_width, $thumb_height)
    {

        $target_path = $target_folder;
        $thumb_path = $thumb_folder . "/";

        $filename_err = explode(".", $_FILES[$field_name]['name']);
        $filename_err_count = count($filename_err);
        $file_ext = $filename_err[$filename_err_count - 1];
        if ($file_name != '') {
            $fileName = $file_name;
        } else {
            $fileName = $_FILES[$field_name]['name'];
        }

        $upload_image = $target_path . basename($fileName);

        if (move_uploaded_file($_FILES[$field_name]['tmp_name'], $upload_image)) {
            if ($thumb == TRUE) {
                $thumbnail = $thumb_path . $fileName;
                list($width, $height) = getimagesize($upload_image);
                $thumb_create = imagecreate($thumb_width, $thumb_height);
                switch ($file_ext) {
                    case 'jpg':
                        $source = imagecreatefromjpeg($upload_image);
                        break;
                    case 'jpeg':
                        $source = imagecreatefromjpeg($upload_image);
                        break;
                    case 'png':
                        $source = imagecreatefrompng($upload_image);
                        break;
                    case 'gif':
                        $source = imagecreatefromgif($upload_image);
                        break;
                    default:
                        $source = imagecreatefromjpeg($upload_image);
                }

                imagecopyresized($thumb_create, $source, 0, 0, 0, 0, $thumb_width, $thumb_height, $width, $height);
                switch ($file_ext) {
                    case 'jpg' || 'jpeg':
                        imagejpeg($thumb_create, $thumbnail, 100);
                        break;
                    case 'png':
                        imagepng($thumb_create, $thumbnail, 100);
                        break;

                    case 'gif':
                        imagegif($thumb_create, $thumbnail, 100);
                        break;
                    default:
                        imagejpeg($thumb_create, $thumbnail, 100);
                }

            }
            $haveThumbUrl = $target_path . "thumb/" . $fileName;
            return $haveThumbUrl;

        } else {
            return FALSE;
        }

    }
    /**
     * @Route("/company/details")
     */
    public function getCompanyDetails(Request $request)
    {
        $id =  $request->get('company_id',null);
        $lang = $request->get('lang',null);
        if ($id || $lang) {
            $em = $this->getDoctrine()->getManager();
            $em->getFilters()->disable('oneLocale');
            $response = array();
            $obj = $this->getDoctrine()->getRepository("AppBundle:Company")->find($id);
            if(!$obj){
                $arr = [
                    'status' => 400,
                    'msg' =>'Invalid Company id ',
                ];
                return new Response(json_encode($arr));
            }
            $lastViewCount =  $obj->getViewCount();
            if($lastViewCount){
                $newCount = $lastViewCount+1;
            }else{
                $newCount = 1;
            }
            $obj->setViewCount($newCount);
            $em->persist($obj);
            $em->flush();
            $ratingObject = $this->getDoctrine()->getRepository("AppBundle:Review")->findOneByCompany($obj);
//            dump($obj); die ;
            $rating = 0;
            if($ratingObject ){
                $rating = $ratingObject->getPercentage();
            }
            $fvtObject = $this->getDoctrine()->getRepository("AppBundle:CompanyFavourite")->findOneByCompany($obj);
            $isFvt = false;
            if ($fvtObject) {
                $isFvt = true;
            }
            /**
             * @var $ratingObject
             * @var $obj Company
             */
            $trans = $obj->getTranslations();
            $output = $trans->get($lang);
            if ($output) {
                $response = array(
                    'c_id' => $obj->getId(),
                    'c_name' => $output->getName(),
                    'c_address' => $obj->getAddress(),
                    'rating' => $rating,
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
                    'c_description' => $obj->getDescription(),
                    'c_opening_time' => $obj->getStartTime(),
                    'c_closing_time' => $obj->getEndTime(),
                    'view_count' => $obj->getViewCount()

                );

                return new Response(json_encode($response));
            } else {
                $response = array(
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
                    'view_count' => $obj->getViewCount()
                );
            }


        }   else{
                $arr = ['data' => array(
                    'result' => 0,
                    'message' => 'Company Id is missing  .(Error Message for Developer only . Translation is not available for Developers Errors)',
                )];
                return new Response(json_encode($arr), 400);
            }
}

/**
 * @param $longitude
 * @param $latitude
 * @param $radius
 * @return array

 */
public function getCompaniesByLongLat($longitude, $latitude, $radius)
{
    /**
     * @var $request Request
     */

    if ($latitude == NULL || $longitude == NULL || $radius == NULL) {
        return ['data' => array(
            'result' => 0,
            'message' => 'Longitude or Latitude or Radius is missing  .(Error Message for Developer only . Translation is not available for Developers Errors)',
        )];
    }
    $em = $this->getDoctrine()->getManager();
    $connection = $em->getConnection();
    $statement = $connection->prepare("SELECT
  id, (
    6371 * acos (
      cos ( radians(:lat) )
      * cos( radians( lat ) )
      * cos( radians( longitude ) - radians(:long) )
      + sin ( radians(:lat) )
      * sin( radians( lat ) )
    )
  ) AS distance
FROM company
HAVING distance < :distance
ORDER BY distance
LIMIT 0 , 20");
    $statement->bindValue('lat', $latitude);
    $statement->bindValue('long', $longitude);
    $statement->bindValue('distance', $radius);
    $statement->execute();
    $results = $statement->fetchAll();
//    dump($results);die;
    $companyObjects = [];
    foreach ($results as $result) {
        $companyObjects[] = $this->getCompanyDetailsInternal($result['id'],'en');
    }
     return $companyObjects;
}


    /**
     * @param null $id
     * @param null $lang
     */
    public function getCompanyDetailsInternal($id = null,$lang = null)
    {
        if ($id || $lang) {
            $em = $this->getDoctrine()->getManager();
//            $em->getFilters()->disable('oneLocale');
            $response = array();
            $obj = $this->getDoctrine()->getRepository("AppBundle:Company")->find($id);
            if(!$obj){
                $arr = [
                    'status' => 400,
                    'msg' =>'Invalid Company id ',
                ];
                return new Response(json_encode($arr));
            }
            $ratingObject = $this->getDoctrine()->getRepository("AppBundle:Review")->findOneByCompany($obj);
//            dump($obj); die ;
            $rating = 0;
            if($ratingObject){
                $rating = $ratingObject->getPercentage();
            }
            $fvtObject = $this->getDoctrine()->getRepository("AppBundle:CompanyFavourite")->findOneByCompany($obj);
            $isFvt = false;
            if ($fvtObject) {
                $isFvt = true;
            }
            /**
             * @var $ratingObject
             * @var $obj Company
             */
            $trans = $obj->getTranslations();
            $output = $trans->get($lang);
            if ($output) {
                $response = array(
                    'c_id' => $obj->getId(),
                    'c_name' => $output->getName(),
                    'c_address' => $obj->getAddress(),
                    'rating' => $rating,
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
                    'c_description' => $obj->getDescription(),
                    'c_opening_time' => $obj->getStartTime(),
                    'c_closing_time' => $obj->getEndTime(),
                    'cat_id' => $obj->getService()->getId(),
                    'view_count' => $obj->getViewCount()

                );


            } else {
                $response = array(
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
                    'cat_id' => $obj->getService()->getId(),
                    'view_count' => $obj->getViewCount()
                );
            }
            return $response;

        }   else{
            $arr = ['data' => array(
                'result' => 0,
                'message' => 'Company Id is missing  .(Error Message for Developer only . Translation is not available for Developers Errors)',
            )];
            return new Response(json_encode($arr), 400);
        }
    }


}
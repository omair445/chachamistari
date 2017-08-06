<?php

namespace AppBundle\Controller;

use AppBundle\Entity\AppUser;
use AppBundle\Entity\AppUserTranslation;
use AppBundle\Entity\LoginTokens;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Templating\EngineInterface;

class DefaultController extends Controller

{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        return $this->redirectToRoute('sonata_admin_dashboard');
    }

    /**
     * @param $locale
     * @param bool $isSuccess
     * @param $object
     * @return array
     */
    public function UserSignUpResponse($locale, $response_status = 0, $object)
    {
        $responseObj = $this->getDoctrine()->getRepository("AppBundle:ServiceResponses")->findBy(array(
            'serviceType' => 'user_register'
        ));
        $locales = null;
        $output = [];
        $response = array();
        $em = $this->getDoctrine()->getManager();
        $em->getFilters()->disable('oneLocale');
        foreach ($responseObj as $obj) {
            $trans = $obj->getTranslations();
            $totalArrayLocales = [$locale];
            $locales = $totalArrayLocales;
            foreach ($locales as $locale1) {
                $output = $trans->get($locale1);
            }
        }
        if (!empty($output)) {
            if ($response_status == 0) {
                $response = array(
                    'data' => array(
                        'result' => 1,
                        'message' => $output->getSuccessMsg(),
                        'u_record' => $object
                    )
                );
            } elseif ($response_status == 1) {
                $response = array(
                    'data' => array(
                        'result' => 0,
                        'message' => $output->getFailureMsg(),
                        'u_record' => $object
                    )
                );
            } elseif ($response_status == 2) {
                $response = array(
                    'data' => array(
                        'result' => 0,
                        'message' => $output->getFailureMsg1(),
                        'u_record' => $object
                    )
                );
            }
        } else {
            $response = array(
                'data' => array(
                    'result' => 0,
                    'message' => 'Please add success and failure response messages from admin panel first, service type is user_login'
                )
            );
        }
        return $response;
    }

    /**
     * @param $locale
     * @param int $response_status
     * @param $object
     * @return array
     */
    public function UserLoginResponse($locale, $response_status = 0, $object)
    {
        $responseObj = $this->getDoctrine()->getRepository("AppBundle:ServiceResponses")->findBy(array(
            'serviceType' => 'user_login'
        ));
        $locales = null;
        $output = [];
        $response = array();
        $em = $this->getDoctrine()->getManager();
        $em->getFilters()->disable('oneLocale');
        foreach ($responseObj as $obj) {
            $trans = $obj->getTranslations();
            $totalArrayLocales = [$locale];
            $locales = $totalArrayLocales;
            foreach ($locales as $locale1) {
                $output = $trans->get($locale1);
            }
        }
        if (!empty($output)) {
            if ($response_status == 0) {
                $response = array(
                    'data' => array(
                        'result' => 1,
                        'message' => $output->getSuccessMsg(),
                        'u_record' => $object
                    )
                );
            } elseif ($response_status == 1) {
                $response = array(
                    'data' => array(
                        'result' => 0,
                        'message' => $output->getFailureMsg(),
                        'u_record' => (object)$object
                    )
                );
            } elseif ($response_status == 2) {
                $response = array(
                    'data' => array(
                        'result' => 0,
                        'message' => $output->getFailureMsg1(),
                        'u_record' => (object)$object
                    )
                );
            }
        } else {
            $response = array(
                'data' => array(
                    'result' => 0,
                    'message' => 'Please add success and failure response messages from admin panel first , service type is user_login'
                )
            );
        }
        return $response;
    }

    /**
     * @param $response
     * @return string
     */
    public function createResponse($response)
    {
        $response = array(
            'data' => $response
        );
        return json_encode($response);
    }

    /**
     * @param $userName
     * @return null
     */
    public function findUserByPhoneNumber($phone)
    {
        $user = $this->getDoctrine()->getRepository("AppBundle:AppUser")->findOneByMobileNumber($phone);
        if (!empty($user)) {
            return $user;
        } else {
            return NULL;
        }
    }

    /**
     * @param $user
     */
    public function removingTokenKeysByUser($user)
    {
        $tokenByUser = $this->getDoctrine()->getManager()->getRepository("AppBundle:LoginTokens")->findByUser($user);
        foreach ($tokenByUser as $tokenObj) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($tokenObj);
            $em->flush();
        }
    }

    /**
     * @param $userId
     * @return array
     */
    public function makeUserObjByUserId($userId, $locale)
    {
        $user = $this->getDoctrine()->getManager()->getRepository("AppBundle:AppUser")->find($userId);
        $locales = null;
        $output = [];
        $em = $this->getDoctrine()->getManager();
//        $em->getFilters()->disable('oneLocale');
        $trans = $user->getTranslations();
        $totalArrayLocales = [$locale];
        $locales = $totalArrayLocales;
        foreach ($locales as $locale1) {
            $output = $trans->get($locale1);
        }
        /**
         * @var $output AppUserTranslation
         */
        $loginToken = $this->getDoctrine()->getRepository("AppBundle:LoginTokens")->findBy(array(
            'user' => $user
        ));
        $token = null;
        foreach ($loginToken as $val) {
            $token = $val->getTokenKey();
        }
        if ($output) {
            $response = [
                'userId' => $user->getId(),
                'token' => $token,
                'firstName' => $output->getFirstName(),
                'lastName' => $output->getLastName(),
                'username' => $output->getFirstName() . ' ' . $output->getLastName(),
                'email' => $user->getEmail(),
                'created' => $user->getCreated(),
                'userstatus' => $user->getStatus()

            ];
        } else {
            $response = [
                'userId' => $user->getId(),
                'token' => $token,
                'firstName' => "",
                'lastName' => "",
                'username' => "",
                'email' => $user->getEmail(),
                'created' => $user->getCreated(),
                'userstatus' => $user->getStatus()
            ];
        }


        return $response;
    }

    /**
     * @param $request
     */
    public function executeLogger($request)
    {
        $logger = $this->get('monolog.logger.doctrine');
        $logger->addNotice('--------------------------------------------------------------------------------');
        $logger->addNotice('--------------------------------------------------------------------------------');
        $logger->addNotice('--------------------------------------------------------------------------------');
        $logger->addNotice('--------------------------------------------------------------------------------');
        $logger->addNotice($request);
        $logger->addNotice('--------------------------------------------------------------------------------');
        $logger->addNotice('--------------------------------------------------------------------------------');
        $logger->addNotice('--------------------------------------------------------------------------------');
        $logger->addNotice('--------------------------------------------------------------------------------');
    }

    /**
     * @param $id
     * @return null
     */
    public function findUserById($id)
    {
        $user = $this->getDoctrine()->getRepository("AppBundle:AppUser")->
        findBy([
                'id' => $id
            ]
        );
        if (!empty($user[0])) {
            return $user[0];
        } else {
            return NULL;
        }
    }

    /**
     * @param $token
     * @return null
     */
    public function getUserByToken($token)
    {
        $userByToken = $this->getDoctrine()->getRepository("AppBundle:LoginTokens")->findOneByTokenKey($token);
        /**
         * @var $userByToken  LoginTokens
         */
        if ($userByToken == NULL) {
            return NULL;
        }
        $loggedInUserId = $userByToken->getUser()->getId();
        $loggedInUser = $this->findUserById($loggedInUserId);
        return $loggedInUser;
    }

    /**
     * @param $token
     * @return bool
     */
    public function checkIfLoggedIn($token)
    {
        $userByToken = $this->getDoctrine()->getRepository("AppBundle:LoginTokens")->findByTokenKey($token);
        if (!empty($userByToken)) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    /**
     * @param $user
     * @param $code
     * @return int
     */
    public function sendEmailOnUserCreate($user, $code)
    {
        /**
         * @var  $user AppUser
         */
        $logger = $this->container->get('logger');
        $mailer = $this->container->get('mailer');
        $message = new \Swift_Message('Account has been created');
        $message->setFrom('support@khidmati.com', 'Khidmati')
            ->setTo($user->getEmail())
            ->setBody("Your account has been created Please activate your account by using this Verification code " . $code);

        $sent = $mailer->send($message);

        if ($sent) {
            $logger->info('Sign up email sent to user  email: ' . $user->getEmail());
        } else {
            $logger->error('Sign up email not sent to user  email: ' . $user->getEmail());
        }

        return $sent;

    }

    /**
     * @param $locale
     * @param int $response_status
     * @param $object
     * @return array
     */
    public function UserActivateAccountResponse($locale, $response_status = 0, $object)
    {
        $responseObj = $this->getDoctrine()->getRepository("AppBundle:ServiceResponses")->findBy(array(
            'serviceType' => 'activate_account'
        ));
        $locales = null;
        $output = [];
        $response = array();
        $em = $this->getDoctrine()->getManager();
        $em->getFilters()->disable('oneLocale');
        foreach ($responseObj as $obj) {
            $trans = $obj->getTranslations();
            $totalArrayLocales = [$locale];
            $locales = $totalArrayLocales;
            foreach ($locales as $locale1) {
                $output = $trans->get($locale1);
            }
        }
        if (!empty($output)) {
            if ($response_status == 0) {
                $response = array(
                    'data' => array(
                        'result' => 1,
                        'message' => $output->getSuccessMsg(),
                        'u_record' => $object
                    )
                );
            } elseif ($response_status == 1) {
                $response = array(
                    'data' => array(
                        'result' => 0,
                        'message' => $output->getFailureMsg(),
                        'u_record' => (object)$object
                    )
                );
            } elseif ($response_status == 2) {
                $response = array(
                    'data' => array(
                        'result' => 0,
                        'message' => $output->getFailureMsg1(),
                        'u_record' => (object)$object
                    )
                );
            }
        } else {
            $response = array(
                'data' => array(
                    'result' => 0,
                    'message' => 'Please add success and failure response messages from admin panel first , service type is user_login'
                )
            );
        }
        return $response;
    }

    /**
     * @param $locale
     * @param int $response_status
     * @return array
     */
    public function UserPasswordReset($locale, $response_status = 0)
    {
//        dump($locale);die;
        $responseObj = $this->getDoctrine()->getRepository("AppBundle:ServiceResponses")->findBy(array(
            'serviceType' => 'user_password_reset'
        ));
        $locales = null;
        $output = [];
        $response = array();
        $em = $this->getDoctrine()->getManager();
        $em->getFilters()->disable('oneLocale');
        foreach ($responseObj as $obj) {
            $trans = $obj->getTranslations();
            $totalArrayLocales = [$locale];
            $locales = $totalArrayLocales;
            foreach ($locales as $locale1) {
                $output = $trans->get($locale1);
            }
        }
        if (!empty($output)) {
            if ($response_status == 0) {
                $response = array(
                    'data' => array(
                        'result' => 1,
                        'message' => $output->getSuccessMsg(),
//                        'u_record' => $object
                    )
                );
            } elseif ($response_status == 1) {
                $response = array(
                    'data' => array(
                        'result' => 0,
                        'message' => $output->getFailureMsg(),
//                        'u_record' => (object)$object
                    )
                );
            } elseif ($response_status == 2) {
                $response = array(
                    'data' => array(
                        'result' => 0,
                        'message' => $output->getFailureMsg1(),
//                        'u_record' => (object)$object
                    )
                );
            }
        } else {
            $response = array(
                'data' => array(
                    'result' => 0,
                    'message' => 'Please add success and failure response messages from admin panel first , service type is user_password_reset'
                )
            );
        }
        return $response;
    }

    /**
     * @param $locale
     * @param int $response_status
     * @param $object
     * @return array
     */
    public function CategoryListingResponse($locale, $response_status = 0, $object)
    {
//        dump($locale);die;
        $responseObj = $this->getDoctrine()->getRepository("AppBundle:ServiceResponses")->findBy(array(
            'serviceType' => 'category_listing'
        ));
        $locales = null;
        $output = [];
        $response = array();
//        $em = $this->getDoctrine()->getManager();
//        $em->getFilters()->disable('oneLocale');
        foreach ($responseObj as $obj) {
            $trans = $obj->getTranslations();
            $totalArrayLocales = [$locale];
            $locales = $totalArrayLocales;
            foreach ($locales as $locale1) {
                $output = $trans->get($locale1);
            }
        }
        if (!empty($output)) {
            if ($response_status == 0) {
                $response = array(
                    'data' => $object
                );
            } elseif ($response_status == 1) {
                $response = array(
                    'data' => array(
                        'result' => 0,
                        'message' => $output->getFailureMsg(),
//                        'u_record' => (object)$object
                    )
                );
            } elseif ($response_status == 2) {
                $response = array(
                    'data' => array(
                        'result' => 0,
                        'message' => $output->getFailureMsg1(),
//                        'u_record' => (object)$object
                    )
                );
            }
        } else {
            $response = array(
                'data' => array(
                    'result' => 0,
                    'message' => 'Please add success and failure response messages from admin panel first , service type is category_listing'
                )
            );
        }
        return $response;
    }

    /**
     * @param $locale
     * @param int $response_status
     * @param $object
     * @return array
     */
    public function locationListingResponse($locale, $response_status = 0, $object)
    {
//        dump($locale);die;
        $responseObj = $this->getDoctrine()->getRepository("AppBundle:ServiceResponses")->findBy(array(
            'serviceType' => 'category_listing'
        ));
        $locales = null;
        $output = [];
        $response = array();
//        $em = $this->getDoctrine()->getManager();
//        $em->getFilters()->disable('oneLocale');
        foreach ($responseObj as $obj) {
            $trans = $obj->getTranslations();
            $totalArrayLocales = [$locale];
            $locales = $totalArrayLocales;
            foreach ($locales as $locale1) {
                $output = $trans->get($locale1);
            }
        }
        if (!empty($output)) {
            if ($response_status == 0) {
                $response = array(
                    'data' => $object
                );
            } elseif ($response_status == 1) {
                $response = array(
                    'data' => array(
                        'result' => 0,
                        'message' => $output->getFailureMsg(),
//                        'u_record' => (object)$object
                    )
                );
            } elseif ($response_status == 2) {
                $response = array(
                    'data' => array(
                        'result' => 0,
                        'message' => $output->getFailureMsg1(),
//                        'u_record' => (object)$object
                    )
                );
            }
        } else {
            $response = array(
                'data' => array(
                    'result' => 0,
                    'message' => 'Please add success and failure response messages from admin panel first , service type is category_listing'
                )
            );
        }
        return $response;
    }

    /**
     * @param $locale
     * @param int $response_status
     * @param $object
     * @return array
     */
    public function areaListingResponse($locale, $response_status = 0, $object)
    {
        $responseObj = $this->getDoctrine()->getRepository("AppBundle:ServiceResponses")->findBy(array(
            'serviceType' => 'area_listing'
        ));
        $locales = null;
        $output = [];
        $response = array();
        foreach ($responseObj as $obj) {
            $trans = $obj->getTranslations();
            $totalArrayLocales = [$locale];
            $locales = $totalArrayLocales;
            foreach ($locales as $locale1) {
                $output = $trans->get($locale1);
            }
        }
        if (!empty($output)) {
            if ($response_status == 0) {
                $response = array(
                    'result' => 1,
                    'message' => $output->getSuccessMsg(),
                    'data' => $object
                );
            } elseif ($response_status == 1) {
                $response = array(
                    'result' => 0,
                    'message' => $output->getFailureMsg(),
                    'data' => $object

                );
            } elseif ($response_status == 2) {
                $response = array(
                    'data' => array(
                        'result' => 0,
                        'message' => $output->getFailureMsg1(),
//                        'u_record' => (object)$object
                    )
                );
            }
        } else {
            $response = array(
                'data' => array(
                    'result' => 0,
                    'message' => 'Please add success and failure response messages from admin panel first , service type is area_listing'
                )
            );
        }
        return $response;
    }

}

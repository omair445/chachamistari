<?php

namespace AppBundle\Controller;

use AppBundle\Entity\AppUser;
use AppBundle\Entity\AppUserTranslation;
use AppBundle\Entity\LoginTokens;
use AppBundle\Entity\User;
use AppBundle\Entity\UserTranslation;
use AppBundle\UUID;
use AppBundle\VerificationCode;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UserController extends DefaultController
{
    /**
     * @Route("/webservice/signup_user")
     */
    public function userSignUpAction(Request $request)
    {

        $firstName = $request->get('fname', NULL);
        $lastName = $request->get('lname', NULL);
        $mobileNumber = $request->get('gsm', NULL);
        $password = $request->get('pass', NULL);
        $locale = $request->get('lang', NULL);
        $email = $request->get('email', NULL);
        if ($firstName != NULL && $lastName != NULL && $mobileNumber != NULL && $password != NULL && $locale != NULL && $email != NULL) {
            $checkIfPhoneNumberExist = $this->getDoctrine()->getRepository('AppBundle:AppUser')->findBy(array(
                'mobileNumber' => $mobileNumber
            ));
            if (!$checkIfPhoneNumberExist) {
                $code = new VerificationCode();
                $verficationCode = $code->generateCode(5);
                $em = $this->getDoctrine()->getManager();
                $user = new AppUser();
                $user->setEmail($email);
                $user->setPassword($password);
                $user->setMobileNumber($mobileNumber);
                $user->setVerificationCode($verficationCode);
                $user->setStatus(FALSE);
                $user->setUserRole('ROLE_USER');
                $em->persist($user);
                $em->flush();
                $id = $user->getId();
                $trans = new AppUserTranslation();
                $trans->setLocale($locale);
                $trans->setFirstName($firstName);
                $trans->setLastName($lastName);
                $trans->setTranslatable($user);
                $em->persist($trans);
                $em->flush();
                $this->sendEmailOnUserCreate($user, $verficationCode);
                $arr = array('userid' => $user->getId(),
                    'username' => $trans->getFirstName(),
                    'userstatus' => $user->getStatus()
                );
                $response = $this->UserSignUpResponse($locale, 0, $arr);
                return new Response(json_encode($response));
            } else {
                return new Response(json_encode($this->UserSignUpResponse($locale, 2, []), JSON_PRETTY_PRINT), 400);
            }
        } else {
            return new Response(json_encode($this->UserSignUpResponse($locale, 1, []), JSON_PRETTY_PRINT), 400);
        }
    }

    /**
     * @Route("/webservice/login_user")
     */

    public function loginUserAction(Request $request)
    {

//        $this->executeLogger(json_encode($request));
        $username = $request->get('username', NULL);
        $password = $request->get('pass', NULL);
        $locale = $request->get('lang', 'en');
        /**
         * @var $user AppUser
         */
        if ($username != NULL && $password != NULL) {
            $userByPhoneNumber = $this->findUserByPhoneNumber($username);
            if ($userByPhoneNumber) {
                $user = $userByPhoneNumber;
                goto a;
            }
            $userByEmail = $this->findUserByEmail($username);
            if ($userByEmail) {
                $user = $userByEmail;
            }
            a:
            if ($user != NULL) {
                if (!$user->getStatus()) {
                    return new Response(json_encode($this->UserLoginResponse($locale, 2, []), JSON_PRETTY_PRINT), 400);
                }
                $tokenObject = new UUID();
                if (md5($password) == $user->getPassword()) {
                    $this->removingTokenKeysByUser($user);
                    $token = $tokenObject->v4();
                    $userToken = new LoginTokens();
                    $userToken->setCreated(new \DateTime('now'));
                    $userToken->setIsEnabled(1);
                    $userToken->setTokenKey($token);
                    $userToken->setUser($user);
                    $em = $this->getDoctrine()->getManager();
                    $em->persist($userToken);
                    $em->flush();


                    return new Response(json_encode($this->UserLoginResponse($locale, 0, $this->makeUserObjByUserId($user->getId(), $locale)), JSON_PRETTY_PRINT), 200);

                } else {
                    return new Response(json_encode($this->UserLoginResponse($locale, 1, []), JSON_PRETTY_PRINT), 400);
                }
            } else {
                return new Response(json_encode($this->UserLoginResponse($locale, 1, []), JSON_PRETTY_PRINT), 400);
            }
        } else {
            $arr = [
                'data' => array(
                    'result' => 0,
                    'message' => 'Parameters Missing .(Error Message for Developer only . Translation is not available for Developers Errors)',
                    'u_record' => (object)[]
                )

            ];
            return new Response(json_encode($arr), 200);
        }
    }

    /**
     * @Route("/webservice/activate_account")
     */
    public function activateAccount(Request $request)
    {
        $code = $request->get('code', null);
        $locale = $request->get('locale', 'en');
        $phoneNumber = $request->get('phoneNumber', NULL);
        if ($code != null && $phoneNumber != null) {
            $user = $this->getDoctrine()->getRepository('AppBundle:AppUser')->findOneByMobileNumber($phoneNumber);
            /**
             * @var $user AppUser
             */
            if (!empty($user)) {
                if ($user->getVerificationCode() == $code) {
                    $em = $this->getDoctrine()->getManager();
                    $user->setStatus(true);
                    $em->persist($user);
                    $em->flush();
                    return new Response(json_encode($this->UserActivateAccountResponse($locale, 0, $this->makeUserObjByUserId($user->getId(), $locale)), JSON_PRETTY_PRINT), 200);

                } else {
                    return new Response(json_encode($this->UserActivateAccountResponse($locale, 1, []), JSON_PRETTY_PRINT), 400);
                }
            } else {
                return new Response(json_encode($this->UserActivateAccountResponse($locale, 2, []), JSON_PRETTY_PRINT), 400);
            }
        } else {
            return new Response(json_encode($this->UserActivateAccountResponse($locale, 2, []), JSON_PRETTY_PRINT), 400);
        }
    }

    /**
     * @param Request $request
     * @return Response
     * @Route("/webservice/reset_password")
     */
    public function resetPassword(Request $request)
    {
        $locale = $request->get('lang', 'en');

        $newPassword = $request->get('newpassword', null);
        $token = $request->get('token', null);
        if ($newPassword != null && $token != null) {
            $user = $this->getUserByToken($token);
            if ($user) {
                /**
                 * @var $user AppUser
                 */
                $em = $this->getDoctrine()->getManager();
                $user->setPassword($newPassword);
                $em->persist($user);
                $em->flush();
                return new Response(json_encode($this->UserPasswordReset($locale, 0), JSON_PRETTY_PRINT), 200);

            } else {
                return new Response(json_encode($this->UserPasswordReset($locale, 2), JSON_PRETTY_PRINT), 400);
            }
        } else {
            return new Response(json_encode($this->UserPasswordReset($locale, 1), JSON_PRETTY_PRINT), 400);
        }

    }

    public function findUserByEmail($email)
    {
        if ($email) {
            $userObject = $this->getDoctrine()->getRepository('AppBundle:AppUser')->findOneByEmail($email);
            if ($userObject) {
                return $userObject;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * @param Request $request
     * @return Response
     * @Route("/webservice/push/settings")
     */
    public function userSettings(Request $request)
    {
        $token = $request->get('token', null);
        $isPushActive = $request->get('isPushActive', FALSE);
        if ($token) {
            $user = $this->getUserByToken($token);
            if ($user) {
                /**
                 * @var $user AppUser
                 */
                $em = $this->getDoctrine()->getManager();
                $user->setIsPushActive($isPushActive);
                $em->persist($user);
                $em->flush();
                $arr = [
                    'code' => 200,
                    'msg' => 'Settings Updated '
                ];
            } else {
                $arr = [
                    'code' => 400,
                    'msg' => 'Invalid Token, Please login again and then try again '
                ];
            }
        } else {
            $arr = [
                'code' => 400,
                'msg' => 'User Token is missing . Or Please Try login and get the token'
            ];
        }
        return new Response(json_encode($arr));
    }

    /**
     * @Route("/webservice/user/profile")
     */

    public function getUserProfile(Request $request)
    {

//        $this->executeLogger(json_encode($request));
        $token = $request->get('token', NULL);
        $locale = $request->get('lang', 'en');
        /**
         * @var $user AppUser
         */
        if ($token) {
            $user = $this->getUserByToken($token);


            return new Response(json_encode($this->makeUserObjByUserId($user->getId(),$locale), JSON_PRETTY_PRINT), 200);

        } else {
            return new Response($arr=['code'=> 200,'msg'=>'Invalid Token']);
        }
    }


}
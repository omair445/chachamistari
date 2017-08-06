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
        $phone = $request->get('gsm', NULL);
        $password = $request->get('pass', NULL);
        $locale = $request->get('lang', 'en');
        /**
         * @var $user AppUser
         */
        if ($phone != NULL && $password != NULL) {
            $user = $this->findUserByPhoneNumber($phone);
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
     * @Route("/webservice/activate_account/{code}/{locale}/{phoneNumber}")
     */
    public function activateAccount(Request $request, $code = null, $locale = 'en', $phoneNumber = null)
    {
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
    public function resetPassword(Request $request )
    {
       $locale = $request->get('lang','en');

       $newPassword = $request->get('newpassword',null);
       $token = $request->get('token',null);
       if($newPassword != null && $token != null){
              $user = $this->getUserByToken($token);
              if($user){
                  /**
                   * @var $user AppUser
                   */
                 $em = $this->getDoctrine()->getManager();
                 $user->setPassword($newPassword);
                 $em->persist($user);
                 $em->flush();
                  return new Response(json_encode($this->UserPasswordReset($locale,0), JSON_PRETTY_PRINT), 200);

              }else{
                  return new Response(json_encode($this->UserPasswordReset($locale, 2), JSON_PRETTY_PRINT), 400);
              }
       }else{
           return new Response(json_encode($this->UserPasswordReset($locale, 1), JSON_PRETTY_PRINT), 400);
       }

    }

}

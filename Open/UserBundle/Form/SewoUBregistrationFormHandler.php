<?php

/*
* This file is part of the HWIOAuthBundle package.
*
* (c) Hardware.Info <opensource@hardware.info>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace Sewolabs\UserBundle\Form;

use FOS\UserBundle\Model\UserManagerInterface,
    HWI\Bundle\OAuthBundle\Form\FOSUBRegistrationFormHandler,
    FOS\UserBundle\Form\Handler\RegistrationFormHandler,
    FOS\UserBundle\Mailer\MailerInterface,
    FOS\UserBundle\Util\TokenGenerator;

use HWI\Bundle\OAuthBundle\OAuth\Response\AdvancedUserResponseInterface,
    HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface;

use Symfony\Component\Form\Form,
    Symfony\Component\HttpFoundation\Request;

/**
* FormHandlerInterface
*
* Interface for objects that are able to handle a form.
*
* @author Justin <justinjohnmathews@gmail.com>
*/
class SewoUBregistrationFormHandler extends FOSUBRegistrationFormHandler
{

    /**
* Processes the form for a given request.
*
* @param Request $request Active request
* @param Form $form Form to process
* @param UserResponseInterface $userInformation OAuth response
*
* @return boolean True if the processing was successful
*/
    public function process(Request $request, Form $form, UserResponseInterface $userInformation)
    {

        //print_r($userInformation);exit;
        $formHandler = $this->reconstructFormHandler($request, $form);

        // make FOSUB process the form already
        $processed = $formHandler->process();

        // if the form is not posted we'll try to set some properties
        if ('POST' !== $request->getMethod()) {
            $user = $form->getData();
             echo $userInformation->getDisplayName();
             //echo $userInformation->getEmail();
            $user->setUsername($this->getUniqueUsername($userInformation->getDisplayName()));
            //$user->setEmail($userInformation->getEmail());
            if ($userInformation instanceof AdvancedUserResponseInterface) {
              //  print_r($userInformation->getEmail());exit;
                $user->setEmail($userInformation->getEmail());
                $eml = $this->userManager->findUserByEmail($userInformation->getEmail());
                if($eml==null){echo "null";
                    $user->setEmail($userInformation->getEmail());
                    $user->setPassword('test');
                    $user->setFacebookId($userInformation->getUsername());
                    $this->userManager->updateUser($user);


                } else{
                    $user->setEmail($userInformation->getEmail());
                    $user->setPassword('test');

                    $this->userManager->updateUser($user);

                }
            }

            $form->setData($user);
        }

        return $processed;
    }



}
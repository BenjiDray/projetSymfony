<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationType;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\BrowserKit\Request;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class SecurityController extends AbstractController
{
   /**
    * @route("/inscription",name="security_registration")
    */
    public function registration(SymfonyRequest $request, ObjectManager $manager,UserPasswordEncoderInterface $encoder)
    {
        $user=new User();

        $form=$this->createForm(RegistrationType::class,$user);
        $form->handleRequest($request);

        if($form->isSubmitted()&& $form->isValid())
        {
            $hash=$encoder->encodePassword($user,$user->getPassword());
            $user->setPassword($hash);
            $manager->persist($user);
            $manager->flush();
            return $this->redirectToRoute("login");
        }
        return $this->render('/security/registration.html.twig',
        [
            'form'=>$form->createView()
        ]);
    }


/**
 * @route("/connexion",name="login")
 */
    public function login()
    {
        return $this->render('/security/login.html.twig');
    }

/**
 * @route("/deconnexion",name="log_out")
 */
public function deconnexion()
{
    return $this->render('/security/login.html.twig');
}
}


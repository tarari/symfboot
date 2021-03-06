<?php

namespace App\Controller;


use App\Form\UserEditType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Form\RegistrationFormType;
use App\Security\LoginAuthenticator;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use App\Entity\User;

class UserController extends AbstractController
{
    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/logout",name="app_logout")
     */
    public function logout(){

    }

    /**
     * @Route("/register", name="app_register")
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder, GuardAuthenticatorHandler $guardHandler, LoginAuthenticator $authenticator): Response
    {
        $user = new User();
        $user->setRoles(['ROLE_USER']);
        $user->setIsActive(true);
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

                //upload file
                /** @var Symfony\Component\HttpFoundation\File\UploadedFile $file */
                $file = $form->get('avatar')->getData();
                 if($file){
                    $fileName = $this->generateUniqueFileName().'.'.$file->guessExtension();
                    // moves the file to the directory where brochures are stored
                    try{
                        $file->move(
                            $this->getParameter('pictures_directory'),
                            $fileName
                        );
                        //updates property to store picture
                        $user->setAvatar($fileName);
                    }catch (FileException $e){
                        $this->addFlash('warning','Error uploading image');
                    }
                    $user->setAvatar($fileName);
            }
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            // do anything else you need here, like send an email

            return $guardHandler->authenticateUserAndHandleSuccess(
                $user,
                $request,
                $authenticator,
                'main' // firewall name in security.yaml
            );
        }

        return $this->render('user/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/profile/{id}",name="app_user_profile")
     */
    public function editUser(Request $request, User $user,  UserPasswordEncoderInterface $passwordEncoder)
    {
        //form edit user
        $form = $this->createForm(UserEditType::class, $user);
        $user = $form->getData();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->getData()->getPlainPassword()) {
                $password = $passwordEncoder->encodePassword($user, $user->getplainPassword());
                $user->setPassword($password);
            }
            //upload file
            $file = $user->getAvatar();
            if ($file) {
                $fileName = $this->generateUniqueFileName() . '.' . $file->guessExtension();
                // moves the file to the directory where brochures are stored
                $file->move(
                    $this->getParameter('pictures_directory'),
                    $fileName
                );
                //updates property to store picture
                $user->setAvatar($fileName);
            }
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            // do anything else you need here, like send an email
            return $this->redirectToRoute('app_admin_users');
        }
        return $this->render('user/profile.html.twig', [
            'form' => $form->createView(),
            'user' => $user
        ]);
    }

    /**
     * @return string
     */
    private function generateUniqueFileName()
    {
        // md5() reduces the similarity of the file names generated by
        // uniqid(), which is based on timestamps
        return md5(uniqid());
    }
}

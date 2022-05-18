<?php

namespace App\Controller;

use App\Entity\ResetPassword;
use App\Form\EmailPasswordResetType;
use App\Form\ResetPasswordType;
use App\Repository\ResetPasswordRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;


class PasswordResetController extends AbstractController
{

    private ResetPasswordRepository $resetPasswordRepository;
    private UserRepository $userRepository;

    /**
     * @param ResetPasswordRepository $resetPasswordRepository
     * @param UserRepository $userRepository
     */
    public function __construct(ResetPasswordRepository $resetPasswordRepository, UserRepository $userRepository)
    {
        $this->resetPasswordRepository = $resetPasswordRepository;
        $this->userRepository = $userRepository;
    }


    #[Route('/password_forgot', name: 'app_password_forgot')]
    public function forgotPassword(Request $request, EntityManagerInterface $em, UserRepository $userRepository, MailerInterface $mailer): Response
    {
        $form = $this->createForm(EmailPasswordResetType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $datas = $form->getData();
            $account = $userRepository->findOneBy(['email' => $datas['email']]);

            if ($account !== null) {
                $token = uniqid('', true);
                $resetPassword = new ResetPassword();
                $resetPassword->setToken($token);
                $resetPassword->setUser($account);
                $resetPassword->setCreatedAt(new \DateTime());
                $em->persist($resetPassword);
                $em->flush();

                $email = new Email();
                $email
                    ->from('comptewow.sisiou@gmail.com')
                    ->to('bombrun.nicolas@gmail.com')
                    ->subject('Changer votre mot de passe')
                    ->html('<p class="color:red"> Cliquer sur le lien pour modifier votre mot de passe <a href="http://127.0.0.1:8000/reset_password/' . $token . '">cliquer ici</a></p>');

                $mailer->send($email);

                return $this->redirectToRoute('app_password_forgot');

            }


        }

        return $this->render('password_reset/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/reset_password/{token}', name: 'app_password_reset')]
    public function ResetPassword(UserPasswordHasherInterface $userPasswordHasher, Request $request,$token, UserRepository $userRepository, EntityManagerInterface $em)
    {

        $resetPassword = $this->resetPasswordRepository->findOneBy(['token' => $token]);
        dump($resetPassword);
        if ($resetPassword !== null) {
            $form = $this->createForm(ResetPasswordType::class);
            $form->handleRequest($request);
            $user = $resetPassword->getUser();
            dump($user);

            if($form->isSubmitted() && $form->isValid()){
                $formResetPassword = $form->getData();
                dump($formResetPassword);

                $user->setPassword(
                    $userPasswordHasher->hashPassword(
                        $user,
                        $formResetPassword['password']
                    )

                );
                $em->persist($user);
                $em->flush();
                
            }
        }


        return $this->render('password_reset/reset.html.twig', [
            'form'=>$form->createView()
        ]);
    }

}

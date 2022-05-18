<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

class TestMailController extends AbstractController
{
    #[Route('/test/mail', name: 'app_test_mail')]
    public function index(MailerInterface $mailer): Response
    {


        return $this->render('test_mail/index.html.twig', [
            'controller_name' => 'TestMailController',
        ]);
    }
}

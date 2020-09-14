<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class EmailController extends AbstractController
{
    /**
     * @Route("/email/send", name="email_send", methods="GET")
     */
  
    public function index( \Swift_Mailer $mailer)
{
    $message = (new \Swift_Message('Hello Email'))
        ->setFrom('stopogaspi.oclock@gmail.com')
        ->setTo('lauriereinette@gmail.com')
        ->setBody(
            $this->renderView(
                'email/index.html.twig', [
                'controller_name' => 'EmailController',
            ]
        ),
        'text/html'
    );
    $mailer->send($message);

    return $this->render('email/index.html.twig');
}
    /**
     * @Route("/email", name="email", methods="GET")
     */
  
    public function index2()
{
    return $this->render('email/index.html.twig');
}


}

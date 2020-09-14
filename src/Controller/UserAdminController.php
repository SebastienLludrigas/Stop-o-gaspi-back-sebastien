<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\ProductPerso;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ProductPersoRepository;

use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/superhero")
<<<<<< 27juillet
*/

class UserAdminController extends AbstractController
{
    /**
     * @Route("/", name="superhero_home")
    */
    public function index(UserRepository $userRepository)
    {
        return $this->render('user_admin/index.html.twig', [
        'users' => $userRepository->findAll(), 'user' => $this->getUser()

        ]);
    }

    /**
     * @Route("/user_edit", name="user_edit")
    */

    public function userEdit()
    {
        
    }

    /**
     * @Route("/user_delete", name="user_delete")
    */

    public function userDelete()
    {

    }

    /**
     * @Route("/user_show", name="user_show")
    */

    public function userShow()
    {

    }
}

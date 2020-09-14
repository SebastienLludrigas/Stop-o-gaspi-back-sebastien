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
 * @Route("/api")
 */


class UserApiSecondController extends AbstractController
{
    public function __construct(Security $security)
    {
        // Avoid calling getUser() in the constructor: auth may not
        // be complete yet. Instead, store the entire Security object.
        $this->security = $security;
    }

    /**
     * @Route("/user/all", name="all_users", methods="GET")
     */
    public function allUsers(UserRepository $userRepository)
    {
        $allUsers = $userRepository->findAll();

        return $this->json($allUsers, 200, ["logged" => true], ['groups' => 'apiv0']);
    }
    /**
     * @Route("/login/signon", name="user_add", methods="POST")
     */
    public function userAdd(\Swift_Mailer $mailer, Request $request, SerializerInterface $serializer, EntityManagerInterface $em, UserPasswordEncoderInterface $encoder, UserRepository $repository)
    {
        $jsonReceived = $request->getContent();

        $user = $serializer->deserialize($jsonReceived, User::class, 'json');

        $json = json_decode($jsonReceived);


        if (filter_var($json->email, FILTER_VALIDATE_EMAIL)) {

            if ($repository->checkEmailUnique($json->email) == null) {

                if ($json->password == $json->verifPassword) {

                    $user->setCreatedAt(new \DateTime());
                    $roles[] = 'ROLE_USER';
                    $user->setRoles($roles);
                    $user->setUsername($json->email);
                    $user->setAlertDay(1);
                    $user->setPassword($encoder->encodePassword($user, $json->password));

                    $em->persist($user);

                    $em->flush();
                    // ici: envoyer un mail de bienvenue à l'utilisateur, avec un lien le redirigean vers le site

                    $userCreated =  $repository->find($user->getId());
                    $body = 'Bienvenue aspirant Gaspi hunter ' . $userCreated->getName() . 'alias'. $userCreated->getPseudo() . " Commence à arreter de gaspiller";

                    $message = (new \Swift_Message('Stop O Gaspi - Inscription: ' . $userCreated->getName()))
                        ->setFrom('lauriereinette@gmail.com')
                        ->setTo($userCreated->getEmail())
                        ->setBody(
                            $body,
                        );
                    $mailer->send($message);

                    return $this->json($userCreated, 200, ["logged" => true], ['groups' => 'apiv0']);
                } else {

                    $error = ["error" => "Les deux mots de pass ne coïcident pas"];

                    return $this->json($error, 403, $error, []);
                }
            }

            $error = ["error" => "L'email entré par l'utilisateur est déjà connu en base de données"];
            return $this->json($error, 403, $error, []);
        }

        $error = ["error" => "L'email entré par l'utilisateur n'est pas un email valide"];
        return $this->json($error, 403, $error, []);
    }
    /**
     * @Route("/login", name="user_login", methods="POST")
     */
    public function login(Request $request, SerializerInterface $serializer, EntityManagerInterface $em, UserPasswordEncoderInterface $encoder, UserRepository $repository)
    {
        $jsonReceived = $request->getContent();

        $json = json_decode($jsonReceived);

        if (filter_var($json->email, FILTER_VALIDATE_EMAIL)) {

            if (!$repository->checkEmailUnique($json->email) == null) {

                $userToLog = $repository->findUserByEmail($json->email);

                $verifBool = password_verify($json->password, $userToLog->getPassword());

                if ($verifBool) {

                    return $this->json($userToLog, 200, ["logged" => true], ['groups' => 'apiv0']);
                } else {

                    $error = [
                        "error" => "Le mot de passe ne coïncide pas avec le mail",
                        "logged" => false
                    ];

                    return $this->json($error, 403, $error, []);
                }
            }

            $error = ["error" => "L'email entré par l'utilisateur n'est pas connu en base de données"];
            return $this->json($error, 403, $error, []);
        }

        $error = ["error" => "L'email entré par l'utilisateur n'est pas un email valide"];
        return $this->json($error, 403, $error, []);
    }
    /**
     * @Route("/user/", name="user_detail", methods="GET")
     */
    public function userView( UserRepository $userRepository)
    {
        $user = $this->getUser();

        if ($user == null) {
            $error = ["error" => "Utilisateur inconnu en base de données"];
            return $this->json($error, $status = 404, $error, $context = []);
        }

        return $this->json($user, 200, [], ['groups' => 'apiv0']);
    }


    /**
     * @Route("/user/product/add/manual", name="product_add_manual", methods="POST")
     */
    public function userProductManualAdd( Request $request, SerializerInterface $serializer, EntityManagerInterface $em, UserRepository $userRepository, ProductPersoRepository $productPersoRepository)
    {
        $user = $this->getUser();

        if ($user == null) {
            $error = ["error" => "Utilisateur inconnu en base de données"];
            return $this->json($error, $status = 404, $error, $context = []);
        }

        $jsonReceived = $request->getContent();

        $newProductPerso = $serializer->deserialize($jsonReceived, ProductPerso::class, 'json');

        $json = json_decode($jsonReceived);

        $newProductPerso->setUser($user);
        $newProductPerso->setCreatedAt(new \DateTime());

        $em->persist($newProductPerso);

        $em->flush();


        return $this->json($userRepository->getAllProductOrderByExpirationDate($user->getId()), 200, ["logged" => true], ['groups' => 'apiv0']);
    }

    /**
     * @Route("/user/product/add/scan", name="product_add_scan", methods="POST")
     */
    public function userProductScanAdd(Request $request, SerializerInterface $serializer, EntityManagerInterface $em, UserRepository $userRepository, ProductPersoRepository $productPersoRepository)
    {
        $user = $this->getUser();

        if ($user == null) {
            $error = ["error" => "Utilisateur inconnu en base de données"];
            return $this->json($error, $status = 404, $error, $context = []);
        }
        $jsonReceived = $request->getContent();

        $newProductPerso = $serializer->deserialize($jsonReceived, ProductPerso::class, 'json');

        $json = json_decode($jsonReceived);

        $newProductPerso->setUser($user);
        $newProductPerso->setCreatedAt(new \DateTime());

        $em->persist($newProductPerso);

        $em->flush();

        return $this->json($userRepository->getAllProductOrderByExpirationDate($user->getId()), 200, ["logged" => true], ['groups' => 'apiv0']);
    }

    /**
     * @Route("/user/product/all/order-by-name-asc", name="product_all_ordre_by_name_asc", methods="GET")
     */
    public function userProductOrderByNameAsc(Request $request, SerializerInterface $serializer, EntityManagerInterface $em, UserRepository $userRepository, ProductPersoRepository $productPersoRepository)
    {
        $user = $this->getUser();
        // dd($user);


        if ($user == null) {
            $error = ["error" => "Utilisateur inconnu en base de données"];
            return $this->json($error, $status = 404, $error, $context = []);
        }


        return $this->json($userRepository->getAllProductOrderByNameAsc($user->getId()), 200, ["logged" => true], ['groups' => 'apiv0']);
    }
    /**
     * @Route("/user/product/all/order-by-name-desc", name="product_all_ordre_by_name_desc", methods="GET")
     */
    public function userProductOrderByNameDesc(Request $request, SerializerInterface $serializer, EntityManagerInterface $em, UserRepository $userRepository, ProductPersoRepository $productPersoRepository)
    {
        $user = $this->getUser();
        // dd($user);


        if ($user == null) {
            $error = ["error" => "Utilisateur inconnu en base de données"];
            return $this->json($error, $status = 404, $error, $context = []);
        }


        return $this->json($userRepository->getAllProductOrderByNameDesc($user->getId()), 200, ["logged" => true], ['groups' => 'apiv0']);
    }
    /**
     * @Route("/user/product/all/order-by-date", name="product_all_ordre_by_date", methods="GET")
     */
    public function userProductOrderByDate(Request $request, SerializerInterface $serializer, EntityManagerInterface $em, UserRepository $userRepository, ProductPersoRepository $productPersoRepository)
    {
        $user = $this->getUser();
        // dd($user);


        if ($user == null) {
            $error = ["error" => "Utilisateur inconnu en base de données"];
            return $this->json($error, $status = 404, $error, $context = []);
        }


        return $this->json($userRepository->getAllProductOrderByExpirationDate($user->getId()), 200, ["logged" => true], ['groups' => 'apiv0']);
    }

    /**
     * @Route("/user/edit/email", name="user_edit_email", methods="POST")
     */
    public function userEditEmail(\Swift_Mailer $mailer, Request $request, SerializerInterface $serializer, EntityManagerInterface $em, UserPasswordEncoderInterface $encoder, UserRepository $userRepository)
    {
        $user = $this->getUser();
        // dd($user);


        if ($user == null) {
            $error = ["error" => "Utilisateur inconnu en base de données"];
            return $this->json($error, $status = 404, $error, $context = []);
        }

        $jsonReceived = $request->getContent();

        $json = json_decode($jsonReceived);

        if (filter_var($json->email, FILTER_VALIDATE_EMAIL)) {

            if ($userRepository->checkEmailUnique($json->email) == null) {

                $verifBool = password_verify($json->password, $user->getPassword());

                if ($verifBool) {
                    
                    $user->setUsername($json->email);
                    $user->setEmail($json->email);

                    $em->flush();
                    // ici: envoyer un mail de bienvenue à l'utilisateur, avec un lien le redirigean vers le site

                    $body =  $user->getName() . ' Ton nouveau mail a été pris en compte';

                    $message = (new \Swift_Message('Stop O Gaspi - Modification email '))
                        ->setFrom('stopogaspi.oclock@gmail.com')
                        ->setTo($user->getEmail())
                        ->setBody(
                            $body,
                        );
                    $mailer->send($message);

                    return $this->json($user, 200, ["logged" => true], ['groups' => 'apiv0']);
                } else {
                    $error = ["error" => "Le mot de pass est incorrect"];

                    return $this->json($error, 403, $error, []);
                }
            }
        }
        $error = ["error" => "L'email entré par l'utilisateur est déjà connu en base de données"];
            return $this->json($error, 403, $error, []);
    }
    
    /**
     * @Route("/user/edit/password", name="user_edit_password", methods="POST")
     */
    public function userEditPassword(\Swift_Mailer $mailer, Request $request, SerializerInterface $serializer, EntityManagerInterface $em, UserPasswordEncoderInterface $encoder, UserRepository $userRepository)
    {
        $user = $this->getUser();
        // dd($user);


        if ($user == null) {
            $error = ["error" => "Utilisateur inconnu en base de données"];
            return $this->json($error, $status = 404, $error, $context = []);
        }

        $jsonReceived = $request->getContent();

        $json = json_decode($jsonReceived);

        $verifBool = password_verify($json->password, $user->getPassword());

            if ($verifBool) {

                if ($json->newPassword == $json->newPasswordVerif) {
                    $user->setPassword($encoder->encodePassword($user, $json->newPassword));
                    
                    $em->flush();
                    // ici: envoyer un mail de bienvenue à l'utilisateur, avec un lien le redirigean vers le site

                    $body =  $user->getName() . ' Ton nouveau mot de passe a été pris en compte, connecte toi avec';

                    $message = (new \Swift_Message('Stop O Gaspi - Modification mot de passe '))
                        ->setFrom('stopogaspi.oclock@gmail.com')
                        ->setTo($user->getEmail())
                        ->setBody(
                            $body,
                        );
                    $mailer->send($message);

                    return $this->json($user, 200, ["logged" => true], ['groups' => 'apiv0']);
                }
            
            $error = ["error" => "Les deux champs nouveau de mot ne sont pas identiques"];

            return $this->json($error, 403, $error, []);

            }
                
            
        $error = ["error" => "Le mot de pass est incorrect"];

        return $this->json($error, 403, $error, []);
                
            
        
    }
        
    /**
     * @Route("/user/edit/alertday/0", name="user_edit_alart0", methods="POST")
     */
    public function userEditAlert0(\Swift_Mailer $mailer, Request $request, SerializerInterface $serializer, EntityManagerInterface $em, UserPasswordEncoderInterface $encoder, UserRepository $userRepository)
    {
        $user = $this->getUser();
        // dd($user);


        if ($user == null) {
            $error = ["error" => "Utilisateur inconnu en base de données"];
            return $this->json($error, $status = 404, $error, $context = []);
        }

        $user->setAlertDay(0);
        
        $em->flush();

        return $this->json($user, 200, ["logged" => true], ['groups' => 'apiv0']);
        
    }

    /**
     * @Route("/user/edit/alertday/1", name="user_edit_alert1", methods="POST")
     */
    public function userEditAlert1(\Swift_Mailer $mailer, Request $request, SerializerInterface $serializer, EntityManagerInterface $em, UserPasswordEncoderInterface $encoder, UserRepository $userRepository)
    {
        $user = $this->getUser();
        // dd($user);


        if ($user == null) {
            $error = ["error" => "Utilisateur inconnu en base de données"];
            return $this->json($error, $status = 404, $error, $context = []);
        }

        $user->setAlertDay(1);
        
        $em->flush();

        return $this->json($user, 200, ["logged" => true], ['groups' => 'apiv0']);
        
    }
    /**
     * @Route("/user/edit/alertday/2", name="user_edit_alert2", methods="POST")
     */
    public function userEditAlert2(\Swift_Mailer $mailer, Request $request, SerializerInterface $serializer, EntityManagerInterface $em, UserPasswordEncoderInterface $encoder, UserRepository $userRepository)
    {
        $user = $this->getUser();
        // dd($user);


        if ($user == null) {
            $error = ["error" => "Utilisateur inconnu en base de données"];
            return $this->json($error, $status = 404, $error, $context = []);
        }

        $user->setAlertDay(2);
        
        $em->flush();

        return $this->json($user, 200, ["logged" => true], ['groups' => 'apiv0']);
        
    }
     /**
     * @Route("/user/edit/name", name="user_edit_name", methods="PUT")
     */
    public function userEditName(\Swift_Mailer $mailer, Request $request, SerializerInterface $serializer, EntityManagerInterface $em, UserPasswordEncoderInterface $encoder, UserRepository $userRepository)
    {
        $user = $this->getUser();
        // dd($user);


        if ($user == null) {
            $error = ["error" => "Utilisateur inconnu en base de données"];
            return $this->json($error, $status = 404, $error, $context = []);
        }

        $jsonReceived = $request->getContent();

        $json = json_decode($jsonReceived);

        $user->setName($json->name);
        
        $em->flush();

        return $this->json($user, 200, ["logged" => true], ['groups' => 'apiv0']);
        
    }

    /**
     * @Route("/user/edit/city", name="user_edit_city", methods="PUT")
     */
    public function userEditCity(\Swift_Mailer $mailer, Request $request, SerializerInterface $serializer, EntityManagerInterface $em, UserPasswordEncoderInterface $encoder, UserRepository $userRepository)
    {
        $user = $this->getUser();
        // dd($user);


        if ($user == null) {
            $error = ["error" => "Utilisateur inconnu en base de données"];
            return $this->json($error, $status = 404, $error, $context = []);
        }

        $jsonReceived = $request->getContent();

        $json = json_decode($jsonReceived);

        $user->setCity($json->city);
        
        $em->flush();

        return $this->json($user, 200, ["logged" => true], ['groups' => 'apiv0']);
        
    }
    /**
     * @Route("/user/edit/pseudo", name="user_edit_pseudo", methods="PUT")
     */
    public function userEditPseudo(\Swift_Mailer $mailer, Request $request, SerializerInterface $serializer, EntityManagerInterface $em, UserPasswordEncoderInterface $encoder, UserRepository $userRepository)
    {
        $user = $this->getUser();
        // dd($user);


        if ($user == null) {
            $error = ["error" => "Utilisateur inconnu en base de données"];
            return $this->json($error, $status = 404, $error, $context = []);
        }

        $jsonReceived = $request->getContent();

        $json = json_decode($jsonReceived);

        $user->setPseudo($json->pseudo);
        
        $em->flush();

        return $this->json($user, 200, ["logged" => true], ['groups' => 'apiv0']);
        
    }

   

    /**
     * @Route("/user/delete", name="user_delete_api", methods="DELETE")
     */
    public function userDelete(\Swift_Mailer $mailer, Request $request, SerializerInterface $serializer, EntityManagerInterface $em, UserPasswordEncoderInterface $encoder, UserRepository $userRepository)
    {
        $user = $this->getUser();

        $email = $user->getEmail();

        if ($user == null) {
            $error = ["error" => "Utilisateur inconnu en base de données"];
            return $this->json($error, $status = 404, $error, $context = []);
        }

        $em->remove($user);

        $em->flush();

        $data= ["message" => "l'utilisateur a bien été supprimé"];

        $response = $this->json($data, 200, [], ['groups' => 'apiv0']);

        return $response;
    }

}

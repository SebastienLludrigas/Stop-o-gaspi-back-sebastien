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
 * @Route("/api/product")
 */


class ProductApiController extends AbstractController
{
    /**
     * @Route("/edit/quantity/{id}", name="product_edit_quantity", requirements={"id" = "\d+"}, methods="POST")
     */
    public function userEditProductPersoQuantity($id, Request $request, SerializerInterface $serializer, EntityManagerInterface $em, UserRepository $userRepository, ProductPersoRepository $productPersoRepository)
    {

        $productPersoToEdit= $productPersoRepository->find($id);


        if ($productPersoToEdit == null) {
            $error = ["error" => "Le produit inconnu en base de données"];
            return $this->json($error, $status = 404, $error, $context = []);
        }
        
        $jsonReceived = $request->getContent();
        
        
        $json = json_decode($jsonReceived);
        
        $productPersoToEdit->setQuantity($json->quantity);


        $em->flush();

        // $productPersoCreated =  $productPersoRepository->find($newProductPerso->getId());

        return $this->json($productPersoToEdit, 200, ["modified" => true], ['groups' => 'apiv0']);

    }

    /**
     * @Route("/edit/expiration-date/{id}", name="product_edit_expiration_date", requirements={"id" = "\d+"}, methods="POST")
     */
    public function userEditProductPersoExpirationDate($id, Request $request, SerializerInterface $serializer, EntityManagerInterface $em, UserRepository $userRepository, ProductPersoRepository $productPersoRepository)
    {

        $productPersoToEdit= $productPersoRepository->find($id);


        if ($productPersoToEdit == null) {
            $error = ["error" => "Le produit inconnu en base de données"];
            return $this->json($error, $status = 404, $error, $context = []);
        }
        $userId = $productPersoToEdit->getUser()->getId();

        $jsonReceived = $request->getContent();

        $json = json_decode($jsonReceived);
        // dd($json->expiration_date);
        $newDate = (new \DateTime($json->expiration_date));
        
        $productPersoToEdit->setExpirationDate($newDate);


        $em->flush();

        
        return $this->json($productPersoToEdit, 200, ["modified" => true], ['groups' => 'apiv0']);

    }

    /**
     * @Route("/delete/{id}", name="product_delete", requirements={"id" = "\d+"}, methods="DELETE")
     */
    public function userDeleteProductPerso($id, Request $request, SerializerInterface $serializer, EntityManagerInterface $em, UserRepository $userRepository, ProductPersoRepository $productPersoRepository)
    {
        //l'id dans l'URL correspond à l'id du produit et non de l'user je récupère donc grace à cet id le produit à supprimer
        $productPersoToDelete= $productPersoRepository->find($id);

        //si le produit n'est pas trouvé je renvoie une 404
        if ($productPersoToDelete == null) {
            $error = ["error" => "Le produit inconnu en base de données"];
            return $this->json($error, $status = 404, $error, $context = []);
        }
        // je récupère l'id de l'user
        $userId = $productPersoToDelete->getUser()->getId();

        // je récupère tous les produits de l'utilisateurs pour les renvoyer au front
        $allProducts = $productPersoRepository->getAllProductOrderByExpirationDate($userId);
        // dd($allProducts); le dd die récupère bien tous les produits

        // je supprime le produits
        $em->remove($productPersoToDelete);    
        //j'envoie en bdd l'info
        $em->flush();

        //je crée un tableau d'info pour le front
        $info = ["info"=>"Le produit a bien été supprimé"];

        // je retourne la réponse 200 avec tous les produits
        return $this->json($allProducts, 200, $info, ['groups' => 'apiv0']);

    }

    /**
     * @Route("/all", name="all_products", methods="GET")
     */
    public function allProducts(UserRepository $userRepository, ProductPersoRepository $productPersoRepository)
    {
        $allProducts = $productPersoRepository->findAll();


        return $this->json($allProducts, 200, ["logged" => true], ['groups' => 'apiv0']);
    }
    
}

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
*/
class ProductAdminController extends AbstractController
{
    /**
     * @Route("/product", name="superhero_product")
     */
    public function index(ProductPersoRepository $productPersoRepository)
    {
        return $this->render('product_admin/index.html.twig', [
            'products' => $productPersoRepository->findAll()
        ]);
    }

    /**
     * @Route("/product_edit", name="product_edit")
     */

    public function productEdit()
    {
        return $this->render('product/edit.html.twig', [
            'controller_name' => 'ProductAdminController',
        ]);
    }

    /**
     * @Route("/product_delete", name="product_delete")
    */

    public function productDelete()
    {
        return $this->render('product/_delete_form.html.twig', [
            'controller_name' => 'ProductAdminController',
        ]);
    }

    /**
     * @Route("/product_show", name="product_show")
    */

    public function productShow()
    {
        # code...
    }
}

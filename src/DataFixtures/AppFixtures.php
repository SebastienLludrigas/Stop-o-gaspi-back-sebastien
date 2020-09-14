<?php

namespace App\DataFixtures;



use App\Entity\ProductPerso;
use App\Entity\User;

use Faker\Factory;
use Faker\FakerRestaurant;

use DateTime;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');

        $faker->addProvider(new \FakerRestaurant\Provider\fr_FR\Restaurant($faker));

        $users = [];
        for ($i = 0; $i < 20; $i++) {
            $user = new User();
            $user->setName($faker->firstName($gender = 'male', 'female'));
            $user->setCity($faker->city);
            $username = $faker->email;
            $user->setEmail($username);
            $user->setUsername($username);
            $user->setPassword($faker->password);
            $user->setAlertDay(random_int(0, 2));
            $user->setRoles(["ROLE_USER"]);
            $user->setPseudo($faker->firstName($gender = 'male', 'female') . random_int(1, 1000) . random_int(1, 1000) . random_int(1, 1000) . random_int(1, 1000));
            $user->setCreatedAt($faker->dateTimeThisMonth($max = 'now', $timezone = null));
            $manager->persist($user);
            $users[] = $user;
        }

        // $brands = [];
        // for ($i = 0 ; $i < 20 ; $i++) {
        //     $brand = new Brand();
        //     $brand->setName($faker->company);
        //     $manager->persist($brand);
        //     // je stock les OBJETS dans un tableau pour les utiliser plus tard
        //     $brands[] = $brand;
        // }

        // $categories = [];
        // $categoryLabels = ["Fruit et légumes", "Charcuterie", "Vienoiserie", "Produit de la mer", "Epicerie fine", "Surgelés", "Boulangerie", "Plats cuisinés", "Junk food"];
        // for ($i = 0 ; $i < count($categoryLabels) ; $i++) {
        //     $category = new Category();
        //     $category->setName($categoryLabels[$i]);
        //     $manager->persist($category);
        //     $categories[] = $category;
        // }

        for ($i = 0; $i < 500; $i++) {
            $productsPerso = [];

            for ($i = 0; $i < 500; $i++) {
                $productPerso = new ProductPerso();
                $productPerso->setName($faker->foodName());
                $productPerso->setExpirationDate($faker->dateTimeBetween($startDate = '+1 days', $endDate = '+ 5 days', $timezone = null));
                $productPerso->setElaborationDate($faker->dateTimeBetween($startDate = '-3 days', $endDate = '+ 0 days', $timezone = null));
                $productPerso->setArchivedDate($faker->dateTimeBetween($startDate = '+10 days', $endDate = '+ 2 months', $timezone = null));
                $productPerso->setElaborationDate($faker->dateTimeBetween($startDate = '+10 days', $endDate = '+ 2 months', $timezone = null));
                $productPerso->setIngredients('Des ingrédients');
                $productPerso->setImage("https://static.openfoodfacts.org/images/products/359/671/041/6028/front_fr.17.100.jpg");
                $productPerso->setQuantity(random_int(1, 30));
                $productPerso->setNutritionalComposition("des bonnes choses dedans beaucoup beaucoup beaucoup de bonnes choses");
                $productPerso->setArchived(random_int(0, 1));
                $productPerso->setExpirated(random_int(0, 1));
                $productPerso->setFavorite(random_int(0, 1));
                $productPerso->setNutriscoreGrade("a");
                $productPerso->setBrand($faker->company);
                $productPerso->setBarcode(random_int(1111111111111, 9999999999999));
                $productPerso->setCreatedAt($faker->dateTimeBetween($startDate = '-10 days', $endDate = 'now', $timezone = null));
                $productPerso->setUser($faker->randomElement($users));
                $manager->persist($productPerso);
                $productsPerso[] = $productPerso;
            }
        }

        $manager->flush();

                // for($i = 0 ; $i < 20 ; $i++) {
                //     $product = new Product();
                //     $product->setName($faker->foodName());
                //     $product->setExpirationDate($faker->dateTimeBetween($startDate = '+10 days', $endDate = '+ 2 months', $timezone = null));
                //     $product->setArchivedDate($faker->dateTimeBetween($startDate = '+10 days', $endDate = '+ 2 months', $timezone = null));
                //     $product->setElaborationDate($faker->dateTimeBetween($startDate = '+10 days', $endDate = '+ 2 months', $timezone = null));
                //     $product->setIngredients('Chocolat, Banane, Mayo, Sel, Poivre');
                //     $product->setQuantity(random_int(1, 30));
                //     $product->setNutritionalComposition(null);
                //     $product->setArchived(random_int(0, 1));
                //     $product->setExpirated(random_int(0, 1));
                //     $product->setFavorite(random_int(0, 1));
                //     $product->setBarcode(random_int(1111111111111, 9999999999999));
                //     $product->setCreatedAt($faker->dateTimeBetween($startDate = '-10 days', $endDate = 'now', $timezone = null));
                //     $product->setBrand($faker->randomElement($brands));
                //     $manager->persist($product);

                //     $productCategories = $faker->randomElements($categories, rand(1, 2));
                //     foreach($productCategories as $categorie) {
                //         $product->addCategory($categorie);
                //     }


                // }
                // for($i = 0 ; $i < 20 ; $i++) {
                //     $product = new Product();
                //     $product->setName($faker->vegetableName());
                //     $product->setExpirationDate($faker->dateTimeBetween($startDate = '+10 days', $endDate = '+ 2 months', $timezone = null));
                //     $product->setArchivedDate($faker->dateTimeBetween($startDate = '+10 days', $endDate = '+ 2 months', $timezone = null));
                //     $product->setElaborationDate($faker->dateTimeBetween($startDate = '+10 days', $endDate = '+ 2 months', $timezone = null));
                //     $product->setIngredients('Chocolat, Banane, Mayo, Sel, Poivre');
                //     $product->setQuantity(random_int(1, 30));
                //     $product->setNutritionalComposition(null);
                //     $product->setArchived(random_int(0, 1));
                //     $product->setExpirated(random_int(0, 1));
                //     $product->setFavorite(random_int(0, 1));
                //     $product->setBarcode(random_int(1111111111111, 9999999999999));
                //     $product->setCreatedAt($faker->dateTimeBetween($startDate = '-10 days', $endDate = 'now', $timezone = null));
                //     $product->setBrand($faker->randomElement($brands));
                //     $manager->persist($product);

                //     $productCategories = $faker->randomElements($categories, rand(1, 2));
                //     foreach($productCategories as $categorie) {
                //         $product->addCategory($categorie);
                //     }



                // }
                // for($i = 0 ; $i < 20 ; $i++) {
                //     $product = new Product();
                //     $product->setName($faker->dairyName());
                //     $product->setExpirationDate($faker->dateTimeBetween($startDate = '+10 days', $endDate = '+ 2 months', $timezone = null));
                //     $product->setArchivedDate($faker->dateTimeBetween($startDate = '+10 days', $endDate = '+ 2 months', $timezone = null));
                //     $product->setElaborationDate($faker->dateTimeBetween($startDate = '+10 days', $endDate = '+ 2 months', $timezone = null));
                //     $product->setIngredients('Chocolat, Banane, Mayo, Sel, Poivre');
                //     $product->setQuantity(random_int(1, 30));
                //     $product->setBarcode(random_int(1111111111111, 9999999999999));
                //     $product->setNutritionalComposition(null);
                //     $product->setArchived(random_int(0, 1));
                //     $product->setExpirated(random_int(0, 1));
                //     $product->setFavorite(random_int(0, 1));
                //     $product->setCreatedAt($faker->dateTimeBetween($startDate = '-10 days', $endDate = 'now', $timezone = null));
                //     $product->setBrand($faker->randomElement($brands));
                //     $manager->persist($product);

                //     $productCategories = $faker->randomElements($categories, rand(1, 2));
                //     foreach($productCategories as $categorie) {
                //         $product->addCategory($categorie);
                //     }



                // }
                // for($i = 0 ; $i < 20 ; $i++) {
                //     $product = new Product();
                //     $product->setName($faker->fruitName());
                //     $product->setExpirationDate($faker->dateTimeBetween($startDate = '+10 days', $endDate = '+ 2 months', $timezone = null));
                //     $product->setArchivedDate($faker->dateTimeBetween($startDate = '+10 days', $endDate = '+ 2 months', $timezone = null));
                //     $product->setElaborationDate($faker->dateTimeBetween($startDate = '+10 days', $endDate = '+ 2 months', $timezone = null));
                //     $product->setIngredients('Chocolat, Banane, Mayo, Sel, Poivre');
                //     $product->setQuantity(random_int(1, 30));
                //     $product->setNutritionalComposition(null);
                //     $product->setArchived(random_int(0, 1));
                //     $product->setExpirated(random_int(0, 1));
                //     $product->setBarcode(random_int(1111111111111, 9999999999999));
                //     $product->setFavorite(random_int(0, 1));
                //     $product->setCreatedAt($faker->dateTimeBetween($startDate = '-10 days', $endDate = 'now', $timezone = null));
                //     $product->setBrand($faker->randomElement($brands));
                //     $manager->persist($product);

                //     $productCategories = $faker->randomElements($categories, rand(1, 2));
                //     foreach($productCategories as $categorie) {
                //         $product->addCategory($categorie);
                //     }



                // }
                // for($i = 0 ; $i < 20 ; $i++) {
                    // $product = new Product();
                    // $product->setName($faker->meatName());
                    // $product->setExpirationDate($faker->dateTimeBetween($startDate = '+10 days', $endDate = '+ 2 months', $timezone = null));
                    // $product->setArchivedDate($faker->dateTimeBetween($startDate = '+10 days', $endDate = '+ 2 months', $timezone = null));
                    // $product->setElaborationDate($faker->dateTimeBetween($startDate = '+10 days', $endDate = '+ 2 months', $timezone = null));
                    // $product->setIngredients('Chocolat, Banane, Mayo, Sel, Poivre');
                    // $product->setQuantity(random_int(1, 30));
                    // $product->setNutritionalComposition(null);
                    // $product->setBarcode(random_int(1111111111111, 9999999999999));
                    // $product->setArchived(random_int(0, 1));
                    // $product->setExpirated(random_int(0, 1));
                    // $product->setFavorite(random_int(0, 1));
                    // $product->setCreatedAt($faker->dateTimeBetween($startDate = '-10 days', $endDate = 'now', $timezone = null));
                    // $product->setBrand($faker->randomElement($brands));
                    // $manager->persist($product);

                    // $productCategories = $faker->randomElements($categories, rand(1, 2));
                    // foreach($productCategories as $categorie) {
                    //     $product->addCategory($categorie);
                    // }



        //      }

        
    }
}

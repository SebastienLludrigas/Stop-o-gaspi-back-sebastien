<?php

namespace App\Repository;

use App\Entity\User;
use App\Entity\ProductPerso;
use App\Repository\ProductPersoRepository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Symfony\Component\Security\Core\User\UserInterface;


/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    private $productPersoRepository;

    public function __construct(ManagerRegistry $registry, ProductPersoRepository $productPersoRepository)
    {
        parent::__construct($registry, User::class);
        $this->productPersoRepository = $productPersoRepository;

    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(UserInterface $user, string $newEncodedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $user->setPassword($newEncodedPassword);
        $this->_em->persist($user);
        $this->_em->flush();
    }
    
    public function checkEmailUnique($email){
        $builder = $this->createQueryBuilder('user');

        $builder->where("user.email = :userEmail");
        
        $builder->setParameter("userEmail", $email);

        $query = $builder->getQuery();

        return $query->getOneOrNullResult();
    }

    public function findUserByEmail($email){
        $builder = $this->createQueryBuilder('user');

        $builder->where("user.email = :userEmail");
        
        $builder->setParameter("userEmail", $email);

        $query = $builder->getQuery();

        return $query->getOneOrNullResult();
    }


    public function userWithAllData($id) {
        
        $builder = $this->createQueryBuilder('user');
        
        $builder->where("user.id = :userId");
        
        $builder->setParameter("userId", $id);

        $builder->leftJoin('user.productPerso', 'productPerso');
        $builder->addSelect('productPerso');

        $query = $builder->getQuery();

        return $query->getOneOrNullResult();
    }
    public function getAllProductOrderByExpirationDate($userId){
    
        return $this->productPersoRepository->getAllProductOrderByExpirationDate($userId);
         
         
     }
    public function getAllProductOrderByNameAsc($userId){
    
       return $this->productPersoRepository->getAllProductOrderByNameAsc($userId);
        
        
    }
    

    public function getAllProductOrderByNameDesc($userId){
    
        return $this->productPersoRepository->getAllProductOrderByNameDesc($userId);
         
         
     }
}

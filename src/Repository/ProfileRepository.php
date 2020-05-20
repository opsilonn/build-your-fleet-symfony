<?php

namespace App\Repository;

use App\Entity\Profile;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Profile|null find($id, $lockMode = null, $lockVersion = null)
 * @method Profile|null findOneBy(array $criteria, array $orderBy = null)
 * @method Profile[]    findAll()
 * @method Profile[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProfileRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Profile::class);
    }




    /**
    * @return Profile Returns the Profile of the user that logged in (or null if it fails)
    * @param email Email of the user logging in
    * @param password Password of the user logging in
    */
    public function logIn($email, $password)
    {
        // We connect to the database's table
        $conn = $this->getEntityManager()->getConnection();

        // We prepare our SQL query
        $sql = "SELECT id, is_admin FROM profile WHERE email = '$email' AND password = '$password'";
        
        // We apply our SQL query
        $stmt = $conn->query($sql)->fetchAll();

        // If we found a match : return the users data (ID, isAdmin)
        if(sizeof($stmt) > 0)
        {
            // We save the data in a Profile structure
            $newProfile = new Profile();
            $newProfile->setId( intval($stmt[0]["id"]) );
            $newProfile->setIsAdmin( $stmt[0]["is_admin"] );

            // We return the Profile of the logged in user
            return $newProfile;
        }
        // Otherwise : return 0 as a default failure value
        else
        {
            null;
        }
    }



    /**
    * @return boolean Returns whether an email exists in the database or not
    * @param email Email we want to know if it exists in the database or not
    */
    public function doEmailExist($email)
    {
        // We connect to the database's table
        $conn = $this->getEntityManager()->getConnection();

        // We check if the email is already taken
        $sql = "SELECT id FROM profile WHERE email = '$email'";

        // we get the data
        return ($conn->query($sql)->fetch() != false);
    }




    // /**
    //  * @return Profile[] Returns an array of Profile objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Profile
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

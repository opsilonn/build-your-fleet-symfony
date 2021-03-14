<?php

namespace App\Repository;

use App\Entity\Spaceship;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Spaceship|null find($id, $lockMode = null, $lockVersion = null)
 * @method Spaceship|null findOneBy(array $criteria, array $orderBy = null)
 * @method Spaceship[]    findAll()
 * @method Spaceship[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SpaceshipRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Spaceship::class);
    }


    /**
     * Find a random amount of Spaceships
     */
    public function findRandomSpaceships($cpt): array
    {
        // BEWARE : we currently ask ALL THE SPACESHIPS, then only take the XxX first.
        // We should only ask the a XxX amount from the database.
        // return array_slice($this->findAll(), 0, $cpt);
        
        // We get all the spaceships
        $spaceshipsFound = $this->findAll();
        
        // If more spaceships are required than it is possible, then we return them all
        if ($cpt > count($spaceshipsFound)) {
            return $spaceshipsFound;
        }
        
        // Otherwise, we randomly set them
        $spaceshipsRandom = array();
        for ($i = 0; $i < $cpt; $i++) {
            // We select a random index
            $rand = rand(0, count($spaceshipsFound) - 1);
            
            // We add to the returning array
            array_push($spaceshipsRandom, $spaceshipsFound[$rand]);
            
            // We remove from the array to select from
            array_splice($spaceshipsFound, $rand, 1);
        }
        
        // We return the list of random spaceships
        return $spaceshipsRandom;


        // We connect to the database's table
        /*
        $conn = $this->getEntityManager()->getConnection();

        // We prepare our SQL query
        $sql = "SELECT * FROM spaceship ORDER BY RAND() LIMIT $cpt";
        
        // We apply or SQL query (singleline, since we don't input any variable)
        $stmt = $conn->query($sql);
    
        // returns an array of arrays (i.e. a raw data set)
        return $stmt->fetchAll();
        */
    }


    /**
     * Selects all distinct Franchises that exist in the database
     */
    public function findAllFranchises(): array
    {
        // We connect to the database's table
        $conn = $this->getEntityManager()->getConnection();

        // We prepare our SQL query
        $sql = "SELECT DISTINCT franchise FROM spaceship
            ORDER BY franchise;";
        
        // We apply or SQL query (singleine, since we don't input any variable)
        $stmt = $conn->query($sql)->fetchAll();

        // We create an array
        $franchises = [];

        // We fill the array with ONLY THE FRANCHISE
        foreach ($stmt as $value) {
            $franchises[] = $value["franchise"];
        }
    
        // We return the franchises
        return $franchises;
    }


    /**
    * @return Spaceship[] Returns an array of Spaceship objects
    */
    public function findByField($filter, $category) : array
    {
        return $this->findBy(["$filter" => "$category"]);
    }
}



/*
$repository = $this->getDoctrine()->getRepository(Product::class);

// look for a single Product by its primary key (usually "id")
$product = $repository->find($id);

// look for a single Product by name
$product = $repository->findOneBy(['name' => 'Keyboard']);
// or find by name and price
$product = $repository->findOneBy([
    'name' => 'Keyboard',
    'price' => 1999,
]);

// look for multiple Product objects matching the name, ordered by price
$products = $repository->findBy(
    ['name' => 'Keyboard'],
    ['price' => 'ASC']
);

// look for *all* Product objects
$products = $repository->findAll();






$input = array("a", "b", "c", "d", "e");

$output = array_slice($input, 2);      // returns "c", "d", and "e"
$output = array_slice($input, -2, 1);  // returns "d"
$output = array_slice($input, 0, 3);   // returns "a", "b", and "c"

// note the differences in the array keys
print_r(array_slice($input, 2, -1));
print_r(array_slice($input, 2, -1, true));
*/

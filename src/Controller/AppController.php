<?php

namespace App\Controller;

use App\Entity\Spaceship;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class AppController extends AbstractController
{
    private $em;


    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }


    /**
     * Page the user first see
     */
    public function homepage(): Response
    {
        // We return the HomePage
        return $this->render('homepage.html.twig');
    }


    /**
     * Main page of the app'
     */
    public function mainpage(Request $request): Response
    {
        $allowed = ['franchise', 'purpose', 'size'];
        $allowedPurposes = ["Personal", "Civil", "Military", "Company", "Scientific"];
        $allowedSizes = ["Satellite", "Individual Spaceship", "Shuttle", "Frigate", "Cruiser", "Space Station"];

        // If we have a criteria to search by :
        if( $request->query->has('sortBy') && $request->query->has('category') )
        {
            // We get the criteria to sort by
            $sortBy = $request->query->get('sortBy');

            // We get the category to search in
            $category = $request->query->get('category');

            // If the criteria is allowed
            if ( in_array($sortBy, $allowed) )
            {
                // We verify that the category is correct (no need to verify for the franchise ^^)
                if($sortBy == "franchise"
                    || ($sortBy == "purpose" && in_array($category, $allowedPurposes))
                    || ($sortBy == "size" && in_array($category, $allowedSizes))
                    )
                {
                    $spaceships = $this->em->getRepository(Spaceship::class)->findByField($sortBy, $category);
                }
                else
                {
                    // Otherwise : We search with an INVALID category
                    throw $this->createNotFoundException("Sorry, but the category you are sorting by is not allowed :(");
                }
            }
            // Otherwise : We sort with an INVALID criteria
            else
            {
                throw $this->createNotFoundException("Sorry, but it seems you tried to sort by unauthorized criterias...");
            }
        }
        else
        {
            // Number of Spaceships to display
            $cpt = 3;
    
            // We get all the spaceships from the database
            $spaceships = $this->em->getRepository(Spaceship::class)->findRandomSpaceships($cpt);
        }

        // We return the Page, to which we send the corresponding arguments
        return $this->render('mainpage.html.twig', [
            'spaceships' => $spaceships
            ]);
    }
}

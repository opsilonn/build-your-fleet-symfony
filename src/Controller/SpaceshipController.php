<?php

namespace App\Controller;

use App\Entity\Pin;
use App\Entity\Spaceship;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class SpaceshipController extends AbstractController
{
    private $em;


    /**
     * Constructor of the Controller
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }



    /**
     * Sorts the Spaceships accordingly to a given factor
     * The method automatically searches for a spaceship of id given accordingly to the route
     */
    public function sort(Request $request): Response
    {
        $allowed = ['franchise', 'purpose', 'size'];
        $allowedPurposes = ["Personal", "Civil", "Military", "Company", "Scientific"];
        $allowedSizes = ["Satellite", "Individual Spaceship", "Shuttle", "Frigate", "Cruiser", "Space Station"];

        // If we have a criteria to search by :
        if( $request->query->has('sortBy') )
        {
            // We get the criteria to sort by
            $sortBy = $request->query->get('sortBy');

            // If the criteria is allowed
            if (in_array($sortBy, $allowed) )
            {
                // Optimised... but not working :(
                /*
                $choices =
                    ($sortBy === "franchise") ? $this->em->getRepository(Spaceship::class)->findAllFranchises()
                    : ($sortBy === "purpose") ? $allowedPurposes 
                    : ($sortBy === "size") ? $allowedSizes 
                    : [];
                */

                // We initialize then set the array of available options
                $choices = [];
                if($sortBy === "franchise")
                {
                    $choices = $this->em->getRepository(Spaceship::class)->findAllFranchises();
                }
                elseif($sortBy === "purpose")
                {
                    $choices = $allowedPurposes;
                }
                elseif($sortBy === "size")
                {
                    $choices = $allowedSizes;
                }

                
                //  we return the page displaying the sorting options
                return $this->render('spaceships/sort.html.twig', [
                    'sortBy' => $sortBy,
                    'choices' => $choices
                    ]);
            }
        }

        // If this statement is reached : send an error
        // stylizedComponent("Sorry, but it seems you tried to access unauthorized data...");
        throw $this->createNotFoundException("You cannot sort Spaceships by this !");
    }


    

    /**
     * Display a SINGLE spaceship (given the ID in the url)
     * The method automatically searches for a spaceship of id given accordingly to the route
     */
    public function show(Spaceship $spaceship): Response
    {
        // If the spaceship was not found (= is null)
        if( !$spaceship )
        {
            throw $this->createNotFoundException('Spaceship #' . $id . ' not found !');
        }

         //  we return the page displaying a SINGLE Spaceship
         return $this->render('spaceships/show.html.twig', compact('spaceship'));
    }



    /**
     * Action linked to a spaceship in order to :
     * - form to create a new instance
     * - verification and addition of the instance to the database
     */
    public function create(Request $request): Response
    {
        // We get the SESSION object
        $session = $this->get('session');
        $isAdmin = ($session->get('isAdmin') != 0);

        // Is an admin
        if($isAdmin)
        {            
            // If we receive a form request : we treat the data
            if($request->isMethod('POST'))
            {
                // We get the data
                $data = $request->request->all();

                // If the data are secured
                if( $this->isCsrfTokenValid('spaceship_create', $data['token']) )
                {
                    // We check if the form's entries are valid
                    // Do more verifications if needed :)
                    $isValid = (isset($data["name"])
                        && isset($data["franchise"])
                        && isset($data["purpose"])
                        && isset($data["size"])
                        && isset($data["price"]));

                    $isPictureSet = isset($_FILES['picture']);

                    // check if the inputs are valid
                    if(!$isValid)
                    {
                        $errorMessage = "Please verify your inputs";
                    }
                    // check if the download went wrong
                    elseif($isPictureSet && $_FILES['picture']['error'] != 0)
                    {
                        $errorMessage = "Something went wrong with the picture, please try again";
                    }
                    // check if the file is not too heavy
                    elseif($isPictureSet && $_FILES['picture']['size'] >= 5*1024*1024)
                    {
                        $errorMessage = "The picture is too heavy, please use a lighter one";
                    }
                    // check if the file is of the correct format
                    elseif($isPictureSet && !in_array(pathinfo($_FILES['picture']['name'])['extension'], array('jpg', 'jpeg', 'png')))
                    {
                        $errorMessage = "The format of your document is not supported ! (only JPG, JPEG or PNG)";
                    }
                    // otherwise : save the instance :)
                    else
                    {
                        // We set the data into a Spaceship
                        $spaceship = new Spaceship;
                        $spaceship->setName($data['name']);
                        $spaceship->setFranchise($data['franchise']);
                        $spaceship->setPurpose($data['purpose']);
                        $spaceship->setSize($data['size']);
                        $spaceship->setSizeCrew( intval($data['sizeCrew']) );
                        $spaceship->setPrice(intval($data['price']) );
                        $spaceship->setAvailable(intval($data['available']) );
                        $spaceship->setHeight(intval($data['height']) );
                        $spaceship->setLength(intval($data['length']) );
                        $spaceship->setWidth(intval($data['width']) );
                        $spaceship->setAssets($data['assets']);
                        $spaceship->setDescription($data['description']);

                        if($isPictureSet)
                        {
                            // We declare the image path in a variable
                            $imagePath = uniqid("img/Uploads/") . "." . pathinfo($_FILES['picture']['name'])['extension'];
                            
                            // We save the picture in the corresponding folder
                            move_uploaded_file($_FILES['picture']['tmp_name'], $imagePath);

                            // We save the path to this picture
                            $spaceship->setImagePath($imagePath);
                        }
                        else
                        {
                            // Default image
                            $spaceship->setImagePath("img/Under_Construction.png");
                        }
        
                        // We save it in the database
                        $this->em->persist($spaceship);
                        $this->em->flush();
                    }
                }
                
                // redirect the user to the mainPage
                return $this->redirectToRoute('mainpage');
            }

            
            return $this->render('spaceships/create.html.twig');
        }
        // Not an Admin : redirected to the mainpage
        else
        {
            // redirect the user to the mainPage
            return $this->redirectToRoute('mainpage');
        }
    }
}
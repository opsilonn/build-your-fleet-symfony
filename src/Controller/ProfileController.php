<?php

namespace App\Controller;

use App\Entity\Profile;
use App\Forms\ProfileLoginType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FormType;

class ProfileController extends AbstractController
{
    private $em;


    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }


    /**
     * Page disconnecting the user
     */
    public function logOut(): Response
    {
        // We get the SESSION object
        $session = $this->get('session');
        $isLogged = $session->get('userID');

        // If the user is logged : he is ALLOWED to log out
        if($isLogged)
        {
            // We reset some SESSION variables
            $session->set('userID', 0);
            $session->set('isAdmin', false);
    
    
            // We display a farewell message
            return $this->render('profiles/logOut.html.twig');
        }
        // Otherwise : we redirect him to the mainpage
        else
        {
            return $this->redirect($this->generateUrl('mainpage'));
        }
    }




    /**
     * Page connecting the user
     * @return Response either the login page or the mainpage correspondingly to what the user is currently doing
     */
    public function logIn(Request $request): Response
    {
        // We get the SESSION object
        $session = $this->get('session');
        $isLogged = ($session->get('userID') != 0);

        // If the user is NOT logged : he is ALLOWED to log in
        if(!$isLogged)
        {
            // POST request -> the form was validated, we now perform some validations
            // = validate (or not) the login
            if ($request->isMethod('POST'))
            {
                // We get the data
                $data = $request->request->all();

                // If the data are not secured : ERROR
                if( !$this->isCsrfTokenValid('profile_login', $data['token']) )
                {
                    $errorMessage = "hmm, this unusual... have you ever heard of CSRF ?";
                }
                // The data are secured : proceed !
                else
                {
                    // We check if the form's entries are valid
                    // Do more verifications if needed :)
                    $isValid = (isset($data["email"]) && isset($data["password"]));

                    // If the data entered by the user are invalid : ERROR
                    if (!$isValid)
                    {
                        $errorMessage = "Please verify your inputs";
                    }
                    // The data are valid : proceed !
                    else
                    {
                        // We verify the inputs with the database
                        $profile = $this->em->getRepository(Profile::class)->logIn($data["email"], $data["password"]);

                        // If the credentials are invalid : ERROR
                        if($profile == null)
                        {
                            $errorMessage = "Invalid credentials, please try again";
                        }
                        // The credentials are valid : proceed !
                        else
                        {
                            // We set some data
                            $session->set('userID', $profile->getId());
                            $session->set('isAdmin', $profile->getIsAdmin());

                            // redirect the user to the mainPage
                            return $this->redirectToRoute('mainpage');
                        }
                    }
                }
                
                // If reached : the login somehow failed
                return $this->render('profiles/logIn.html.twig', ['errorMessage' => $errorMessage]);
                //return $this->redirectToRoute('profile_logIn');
            }

            // The user just arrived on the page :
            // => We display the form (no initial error message)
            return $this->render('profiles/logIn.html.twig');
        }
        // Otherwise : not allowed to be here !
        else
        {
            // redirect the user to the mainPage
            return $this->redirectToRoute('mainpage');
        }
    }




    /**
     * Page creating a user account
     * @return Response either the signin page or the mainpage correspondingly to what the user is currently doing
     */
    public function signIn(Request $request): Response
    {
        // We get the SESSION object
        $session = $this->get('session');
        $isLogged = ($session->get('userID') != 0);

        // If the user is NOT logged : he is ALLOWED to sign in
        if(!$isLogged)
        {
            // POST request -> the form was validated, we now perform some validations
            // = validate (or not) the signin
            if ($request->isMethod('POST'))
            {
                // We get the data
                $data = $request->request->all();

                // If the data are not secured : ERROR
                if( !$this->isCsrfTokenValid('profile_signin', $data['token']) )
                {
                    $errorMessage = "hmm, this unusual... have you ever heard of CSRF ?";
                }
                // The data are secured : proceed !
                else
                {
                    // We check if the form's entries are valid
                    // Do more verifications if needed :)
                    $isValid = (isset($data["fName"])
                        && isset($data["lName"])
                        && isset($data["email"])
                        && isset($data["password"]));

                    // If the data entered by the user are invalid : ERROR
                    if (!$isValid)
                    {
                        $errorMessage = "Please verify your inputs";
                    }
                    // The data are valid : proceed !
                    else
                    {
                        // We try the Sign in method
                        // If it returns data : ERROR, the email is already taken
                        if($this->em->getRepository(Profile::class)->doEmailExist($data["email"]))
                        {
                            $errorMessage = "Email already taken, please try again";
                        }
                        // If it returns nothing : proceed
                        else
                        {
                            // we define a Profile instance
                            $newProfile = new Profile();
                            $newProfile->setFName($data["fName"]);
                            $newProfile->setLName($data["lName"]);
                            $newProfile->setEmail($data["email"]);
                            $newProfile->setPassword($data["password"]);
                            
                            // We push it into the database
                            $this->em->persist($newProfile);
                            $this->em->flush();

                            // We set some data in the app's session
                            $session->set('userID', $newProfile->getId());
                            $session->set('isAdmin', $newProfile->getIsAdmin());

                            // redirect the user to the mainPage
                            return $this->redirectToRoute('mainpage');
                        }
                    }
                }
                
                // If reached : the signin somehow failed
                return $this->render('profiles/signin.html.twig', ['errorMessage' => $errorMessage]);
            }

            // The user just arrived on the page :
            // => We display the form (no initial error message)
            return $this->render('profiles/signin.html.twig');
        }
        // Otherwise : not allowed to be here !
        else
        {
            // redirect the user to the mainPage
            return $this->redirectToRoute('mainpage');
        }
    }




    /**
     * Display a SINGLE Profile (given the ID in the url)
     * The method automatically searches for a spaceship of id given accordingly to the route
     */
    public function show(Profile $profile, Request $request): Response
    {
        // If the spaceship was not found (= is null)
        if( !$profile )
        {
            throw $this->createNotFoundException('Profile #' . $profile->getID() . ' not found !');
        }

        // We get the SESSION object
        $session = $this->get('session');
        $userID = $session->get('userID');


        // If the user tries to access his data : proceed
        if($profile->getID() == $userID)
        {
            // POST : a SPECIFIC button was pressed...
            if($request->isMethod('POST'))
            {
                // Modify profile
                if( isset($_POST['profileModifyStart']) )
                {
                    return $this->render('profiles/show.html.twig', ['profile' => $profile, 'modify' => true]);
                }
                // Drop all modifications
                if( isset($_POST['profileModifyCancel']) )
                {
                    return $this->render('profiles/show.html.twig', ['profile' => $profile, 'modify' => false]);
                }
                // Drop all modifications
                if( isset($_POST['profileModifySave']) )
                {
                    // We get the data
                    $data = $request->request->all();

                    // We check if the form's entries are valid
                    // Do more verifications if needed :)
                    $isValid = (isset($data["fName"])
                        && isset($data["lName"])
                        && isset($data["email"])
                        && isset($data["password"])
                        && isset($data["galaxy"])
                        && isset($data["system"])
                        && isset($data["planet"])
                        && isset($data["address"])
                        && isset($data["description"])
                    );

                    // If the data entered by the user is valid : proceed
                    if ($isValid)
                    {
                        // we define a Profile instance
                        $newProfile = new Profile();
                        $newProfile->setId($userID);
                        $newProfile->setFName($data["fName"]);
                        $newProfile->setLName($data["lName"]);
                        $newProfile->setEmail($data["email"]);
                        $newProfile->setPassword($data["password"]);
                        $newProfile->setGalaxy($data["galaxy"]);
                        $newProfile->setSystem($data["system"]);
                        $newProfile->setPlanet($data["planet"]);
                        $newProfile->setAddress($data["address"]);
                        $newProfile->setDescription($data["description"]);
                        
                        // We push it into the database
                        $this->em->merge($newProfile);
                        $this->em->flush();

                        // redirect to showing the data, but cannot modify them
                    return $this->render('profiles/show.html.twig', ['profile' => $profile, 'modify' => false]);
                    }
                }
                // Delete the Profile
                if( isset($_POST['profileDelete']) )
                {
                    // We remove the Profile from the database
                    $this->em->remove($profile);
                    $this->em->flush();

                    
                    // We reset some SESSION variables
                    $session->set('userID', 0);
                    $session->set('isAdmin', false);

                    // redirect the user to the mainPage
                    return $this->redirectToRoute('mainpage');
                }
                
                dd("how did you come here");
            }
            // GET : the user just arrived on the page
            else
            {
                return $this->render('profiles/show.html.twig', ['profile' => $profile, 'modify' => false]);
            }
        }
        // otherwise : ERROR
        else
        {
            throw $this->createNotFoundException('You are not allowed to see the profile of ID #' . $profile->getID() . ' !');
        }
    }
}

<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Depot;
use App\Entity\Compte;
use App\Entity\Profil;
use App\Form\UserType;
use App\Form\DepotType;
use App\Form\CompteType;
use App\Entity\Partenaire;
use App\Form\UserFormType;
use App\Form\PartenaireType;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\Entity;
use App\Repository\UserRepository;
use App\Repository\CompteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\FileBag;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Exception\JWTEncodeFailureException;
    /**
     * @Route("/api", name="api")
     */
class UserController extends AbstractController
{
    private $passwordEncoder;


//================================AJOUTER USER COMPTE PARTENAIRE===========================£================================================================//

    /**
     * @Route("/register", name="register", methods={"POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $partenaire = new Partenaire();
        $form = $this->createForm(PartenaireType::class, $partenaire);
        $data = $request->request->all();
        $form->submit($data);
        $partenaire->setStatut("debloquer");
        $entityManager->persist($partenaire);
        $entityManager->flush();
      
        $compte = new Compte();
        $form = $this->createForm(CompteType::class, $compte);
        $data = $request->request->all();
        $form->submit($data);
        $compte->setSolde(1);
        $a="W";
        $number = $a.rand(1000000000, 9999999999);
        $compte->setNumcompte($number);
        $compte->setPartenaire($partenaire);
        $entityManager = $this->getDoctrine()->getManager();

        $utilisateur = new User();
        $form = $this->createForm(UserType::class, $utilisateur);
        $form->handleRequest($request);
        $data = $request->request->all();
        $form->submit($data);

        $files = $request->files->all()['imageName'];
        $utilisateur->setImageFile($files);

        $utilisateur->setRoles(["ROLE_ADMIN"]);
        $utilisateur->setStatut("debloquer");

        $utilisateur->setPartenaire($partenaire);
        $utilisateur->setCompte($compte);
       
        $utilisateur->setPassword(
            $passwordEncoder->encodePassword(
                $utilisateur,
              $form->get('password')->getData()
            )
        );
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($compte);
        $entityManager->persist($utilisateur);
        $entityManager->flush();
        return new Response('Admin  ajouté  avec succès', Response::HTTP_CREATED);
    }
    //================================Faire un dépot d'argent===========================£================================================================//
     /**
     * @Route("/depot", name="depot",methods={"POST"})
     */
    public function depot(Request $request,EntityManagerInterface $entityManager,CompteRepository $reposi ): Response
        {
    
            $depot = new Depot();
            $form = $this->createForm(DepotType::class, $depot);
            $data=$request->request->all();
            $form->submit($data);
            $depot->setDatedepot(new \DateTime());
            $repo=$this->getDoctrine()->getRepository(Compte::class);
            $compt=$repo->find($data["compte"]);
            
            $compt->setSolde($compt->getSolde()+$depot->getMontant());
           
            $depot->setCompte($compt);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($depot); 
            $entityManager->persist($compt);
    
            $entityManager->flush();
           
            
            return new Response('Le depot a été effectuer',Response::HTTP_CREATED);
        }
//================================Faire ajout Caissier===========================£================================================================//
 /**
     * @Route("/caissier", name="caissier",methods={"POST"})
     */
    public function ajoutcaissier (Request $request, EntityManagerInterface $entityManager, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $partenaire = new Partenaire();
        $form = $this->createForm(PartenaireType::class, $partenaire);
        $data = $request->request->all();
        $form->submit($data);
        $partenaire->setStatut("debloquer");
        $entityManager->persist($partenaire);
        $entityManager->flush();
       
        $compte = new Compte();
        $form = $this->createForm(CompteType::class, $compte);
        $data = $request->request->all();
        $form->submit($data);
        $compte->setSolde(1);
        $a="W";
        $number = $a.rand(1000000000, 9999999999);
        $compte->setNumcompte($number);
        $compte->setPartenaire($partenaire);
        $entityManager = $this->getDoctrine()->getManager();

        $utilisateur = new User();
        $form = $this->createForm(UserType::class, $utilisateur);
        $form->handleRequest($request);
        $data = $request->request->all();
        $form->submit($data);

        $files = $request->files->all()['imageName'];
        $utilisateur->setImageFile($files);

        $utilisateur->setRoles(["ROLE_CAISSIER"]);
        $utilisateur->setStatut("debloquer");

        $utilisateur->setPartenaire($partenaire);
        $utilisateur->setCompte($compte);
       
        $utilisateur->setPassword(
            $passwordEncoder->encodePassword(
                $utilisateur,
              $form->get('password')->getData()
            )
        );
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($compte);
        $entityManager->persist($utilisateur);
        $entityManager->flush();
        return new Response('Vous avez ajouté avec succès un caissier', Response::HTTP_CREATED);
    }

//================================AJOUTER USER===========================£================================================================//
     /**
     * @Route("/user", name="user",methods={"POST"})
     */
public function ajoutuser(Request $request, UserPasswordEncoderInterface $passwordEncoder, EntityManagerInterface $entityManager)
        {
            $user = new User();
            $form = $this->createForm(UserType::class, $user);
            $form->handleRequest($request);
            $values=$request->request->all();
            $form->submit($values);
            
            $file=$request->files->all()['imageName'];
    
            if ($form->isSubmitted() ) {
                $user->setPassword(
                    $passwordEncoder->encodePassword(
                    $user,
                    $form->get('password')->getData()
    
                ));
                
    
                $user->setImageFile($file);
                
                $repos=$this->getDoctrine()->getRepository(Profil::class);
                $profils=$repos->find($values['profil']);
                $user->setProfil($profils);
    
                if($profils->getLibelle() == "admin"){
                    $user->setRoles(["ROLE_ADMIN"]);  
                }
                elseif($profils->getLibelle() == "caissiere"){
                    $user->setRoles(["ROLE_CAISSIER"]);
                }
                elseif($profils->getLibelle() == "user"){
                    $user->setRoles(["ROLE_USER"]);
                }
                
                $user->setStatut("debloquer");
    
                    $entityManager = $this->getDoctrine()->getManager();
                    $entityManager->persist($user);
                    $entityManager->flush();
        
                    $data = [
                        'statu' => 201,
                        'messag' => 'L\'utilisateur a été créé'
                    ];
            
                    return new JsonResponse($data, 201);
            }
            $data = [
                'statu' => 500,
                'messag' => 'Erreur lors de l\'insertion'
            ];
    
            return new JsonResponse($data, 500);
     }

}
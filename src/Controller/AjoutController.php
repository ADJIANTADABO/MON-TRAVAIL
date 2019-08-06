<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Depot;
use App\Entity\Compte;
use App\Entity\Profil;
use App\Entity\Partenaire;
use App\Repository\UserRepository;
use App\Repository\PartenaireRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response ;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
     * @Route("/api", name="api")
     */
class AjoutController extends AbstractController
{
    
  
//==============Ajouter un partenaire et user====================================£============================================================//    
    /**
     * @Route("/partenaireuser", name="partenaire", methods={"POST"})
     */
    public function partuser(Request $request, UserPasswordEncoderInterface $passwordEncoder, EntityManagerInterface $entityManager, SerializerInterface $serializer, ValidatorInterface $validator){
        $random=random_int(100000,999999);
        $values = json_decode($request->getContent());
    
//AJOUT PARTENAIRE
        $part = new Partenaire();
       
        $part->setRaisonsocial($values->raisonsocial);
        $part->setEntreprise($values->entreprise);
        $part->setNinea($values->ninea);
        $part->setAdresse($values->adresse1);
        $part->setStatut($values->statut1);
        $part->setTelephone($values->telephone1);

//AJOUT DANS LA TABLE USER
        $user = new User();

        $user->setUsername($values->username);
        $user->setPassword($passwordEncoder->encodePassword($user, $values->password));
        $user->setPrenom($values->prenom);
        $user->setNom($values->nom);
        $user->setEmail($values->email);
        $user->setTelephone($values->telephone);
        $user->setAdresse($values->adresse);
        $user->setCni($values->cni);
        $user->setStatut($values->statut);
        //$user->setRoles(['ROLE_ADMIN']);
        $user->setPartenaire($part);
        

        $repo = $this->getDoctrine()->getRepository(Profil::class);
        $profils=$repo->find($values->profil);
        $user->setProfil($profils);
        $role=[];
        if($profils->getLibelle() == "ADMIN"){
          $role=["ROLE_ADMIN"];  
        }
        $user->setRoles($role);

//AJOUT DANS LA TABLE COMPTE 

        $compte = new Compte();
        $compte->setNumcompte($random);
        $compte->setSolde($values->solde);

        $compte->setPartenaire($part);
        $entityManager->persist($user);
        $entityManager->persist($part);
        $entityManager->persist($compte);
        $entityManager->flush();

        $entityManager = $this->getDoctrine()->getManager();

        $data = [
            'statu' => 201,
            'messag' => 'L\'utilisateur a été créé'
        ];

        return new JsonResponse($data, 201);
    }

//================================Faire un dépot d'argent===========================£================================================================//
     /**
     * @Route("/depoTcompte", name="depot")
     */
    public function depot(Request $request,EntityManagerInterface $entityManager)
    {
        $values = json_decode($request->getContent());
        if(isset($values->montant)) {

            $depot = new Depot();
        if(($values->montant)>=75000){
            $depot->setMontant($values->montant);
            $depot->setDatedepot(new \DateTime());

            $repo = $this->getDoctrine()->getRepository(Compte::class);
            $compte = $repo->find($values->compte);
            $depot->setCompte($compte);

            $compte->setSolde($compte->getSolde() + $values->montant);

            $entityManager->persist($compte);

            

            $entityManager->persist($depot);
            $entityManager->flush();

            $data = [
                'status' => 201,
                'message' => 'Le depot est fait avec succées'
            ];
            return new JsonResponse($data, 201);
            
        }
    else {
        $data = [
            'status1' => 500,
            'message1' => 'depot invalide le montant est supeier a 75000'
        ];
        return new JsonResponse($data, 500);
         }
        }
    }
//==============================Bloquer et Debloquer========================£======================================================================================================//
    /**
     * @Route("/bloquer", name="updatparten", methods={"POST"})
     * @Route("/debloquer", name="updat", methods={"POST"})
    */
public function userBloquer(Request $request, UserRepository $userRepo,EntityManagerInterface $entityManager): Response
    {
        $values = json_decode($request->getContent());
        $user=$userRepo->findOneByUsername($values->username);
        echo $user->getStatut();
        if($user->getStatut()=="bloquer"){
            if($user->getProfil()=="admin"){
                $user->setRoles(["ROLE_ADMIN"]);
            }
            elseif($user->getProfil()=="user"){
                $user->setRoles(["ROLE_USER"]);
            }
            $user->setStatut("debloquer");
            }
                // else{
                //     $user->setStatut("bloquer");
                //     $user->setRoles(["ROLE_USERLOCK"]);
         

        $entityManager->flush();
        $data = [
            'status' => 200,
            'message' => 'utilisateur a changé de statut (bloqué/débloqué)'
        ];
        return new JsonResponse($data);
    
    }
//==============================Lister Partenaire========================£======================================================================================================//
/**
     * @Route("/listParten", name="listpartenaire", methods={"GET"})
     */
    public function listParten(PartenaireRepository $partenaireRepository, SerializerInterface $serializer)
    {
        $partenaires = $partenaireRepository->findAll();
        $data = $serializer->serialize($partenaires, 'json');

        return new Response($data, 200, [
            'Content-Type' => 'application/json'
        ]);
    }
//======================================================================================================================================================//

}


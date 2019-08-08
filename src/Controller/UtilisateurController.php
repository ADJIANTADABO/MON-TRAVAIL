<?php
namespace App\Controller;

use App\Entity\User;
use App\Entity\Compte;
use App\Entity\Profil;
use App\Entity\Partenaire;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response ;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;



/**
 * @Route("/api")
 */
class UtilisateurController extends AbstractController
{
//==================Login================================£=========================================================================================//
    /**
     * @Route("/login", name="login", methods={"POST"})
     */
    public function login(Request $request)
    {
        $user = $this->getUser();
        return $this->json([

            'roles' => $user->getRoles(),
            'username' => $user->getUsername()
        ]);
    }
//====================Ajouter utilisateur==================================£========================================================================================================================£
    /**
     * @Route("/utilisateur", name="register", methods={"POST"})
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder, EntityManagerInterface $entityManager)
    {
        $values = json_decode($request->getContent());
       
        if(isset($values->username,$values->password)) {
            $user = new User();
            $user->setUsername($values->username);
            $user->setPassword($passwordEncoder->encodePassword($user,$values->password));
            // $user->setRoles(["ROLE_ADMIN"]);
            $user->setNom($values->nom);
            $user->setPrenom($values->prenom);
            $user->setAdresse($values->adresse);
            $user->setEmail($values->email);
            $user->setTelephone($values->telephone);
            $user->setCni($values->cni);
            $user->setStatut($values->statut);
            $user->setImageName("null");
            $user->setUpdatedAt(new \DateTime('now'));
            
            
            $repo = $this->getDoctrine()->getRepository(Partenaire::class);
            $partenaires=$repo->find($values->partenaire);
            $user->setPartenaire($partenaires);

            $repo = $this->getDoctrine()->getRepository(Profil::class);
            $profils=$repo->find($values->profil);
            $user->setProfil($profils);
            $role=[];
            if($profils->getLibelle() == "CAISSIERS"){
              $role=["ROLE_CAISSIER"];  
            }
            elseif($profils->getLibelle() == "ADMIN"){
                $role=["ROLE_ADMIN"];
            }
            elseif($profils->getLibelle() == "USER"){
                $role=["ROLE_USER"];
            }
            $user->setRoles($role);

            $repo = $this->getDoctrine()->getRepository(Compte::class);
            $comptes=$repo->find($values->compte);
            $user->setCompte($comptes);

            $entityManager->persist($user);
            $entityManager->flush();

            $data = [
                'statuts' => 201,
                'message1' => 'L\'utilisateur a été créé'
            ];

            return new JsonResponse($data, 201);
        }
        $data = [
            'statut' => 500,
            'messag' => 'Vous devez renseigner les clés d\'utilisateur et mot de passe'
        ];
        return new JsonResponse($data, 500);
    }
//====================Ajouter Profil==================================£========================================================================================================================£
        /** 
        * @Route("/profil", name="profil", methods={"POST"})
        */
    public function ajoutprofil(Request $request, SerializerInterface $serializer, EntityManagerInterface $entityManager)
        {
        $profils = $serializer->deserialize($request->getContent(), Profil::class, 'json');
        $entityManager->persist($profils);

        $entityManager->flush();
        $data = [
            'status' => 201,
            'message' => 'Le Profil a bien été ajouté'
        ];
        return new JsonResponse($data, 201);
        }
//====================Ajouter Compte=============================================£========================================================================================================================£
    /** 
    * @Route("/compte", name="compte", methods={"POST"})
    */
        public function ajoutcompte(Request $request, SerializerInterface $serializer, EntityManagerInterface $entityManager)
        {
        $compte = new Compte();
        $random=random_int(100000,999999);
        $values = json_decode($request->getContent());
        $compte->setNumcompte($values->numcompte);
        $compte->setSolde($values->solde);

        $repo=$this->getDoctrine()->getRepository(Partenaire::class);
        $partenaires=$repo->find($values->partenaire);
        $compte->setPartenaire($partenaires);

        $entityManager->persist($compte);

        $entityManager->flush();
        $data = [
            'status' => 201,
            'message' => 'Le compte a bien été ajouté'
        ];
        return new JsonResponse($data, 201);
        }

   
//======================================================================================================================================================//

    }

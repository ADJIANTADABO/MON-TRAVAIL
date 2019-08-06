<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Depot;
use App\Form\UserType;
use App\Form\DepotType;
use App\Entity\Partenaire;
use App\Form\UserFormType;
use App\Form\PartenaireType;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\FileBag;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
    /**
     * @Route("/api", name="api")
     */
class UserController extends AbstractController
{
    /**
     * @Route("/register", name="register")
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
        //recuperation de l id du partenaire//
        $repository = $this->getDoctrine()->getRepository(Partenaire::class);
        $part = $repository->find($partenaire->getId());

        $compte = new Depot();
        $form = $this->createForm(DepotType::class, $compte);
        $data = $request->request->all();
        $form->submit($data);
        $compte->setSolde(1);
        $num = rand(1000000000, 9999999999);
        $sn = "SN";
        $number = $sn . $num;
        $compte->setNumCompte($number);
        $compte->setPartenaire($partenaire);
        $entityManager = $this->getDoctrine()->getManager();

        $utilisateur = new User();
        $form = $this->createForm(UserType::class, $utilisateur);
        $form->handleRequest($request);
        $files = $request->files->all()['imageName'];
        $form->submit($data);
        $utilisateur->setImageFile($files);

        $utilisateur->setRoles(["ROLE_ADMIN"]);
        $utilisateur->setPartenaire($partenaire);
        $utilisateur->setStatut("debloquer");
        $utilisateur->setPassword(
            $passwordEncoder->encodePassword(
                $utilisateur,
                $form->get('plainPassword')->getData()
            )
        );
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($compte);
        $entityManager->persist($utilisateur);
        $entityManager->flush();
        return new Response('Admin  ajouté  avec succès', Response::HTTP_CREATED);
    }
}

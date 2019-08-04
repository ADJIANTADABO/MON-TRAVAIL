<?php

namespace App\DataFixtures;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class UserFixtures extends Fixture
{
   // src/DataFixtures/AppFixtures.p// ..
private $encoder;

public function __construct(UserPasswordEncoderInterface $encoder)
{
    $this->encoder = $encoder;
}

// ...
public function load(ObjectManager $manager)
{
    $user = new User();
    $user->setUsername('superadmin');
    $user->setRoles(['ROLES_SUPER_ADMIN']);

    $password = $this->encoder->encodePassword( $user, 'superadmin');
    $user->setPassword($password);
    $user->setPrenom("ADJI");
    $user->setNom("DABO");
    $user->setEmail("dablisco@gmail.com");
    $user->setAdresse("Ndiakhirate");
    $user->setTelephone(772467662);
    $user->setCni(1994121212);
    $user->setStatut("Actif");
    $manager->persist($user);
    $manager->flush();
}

}

<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Exception\JWTEncodeFailureException;

/**
 * @Route("/api")
 */
class AdjiController extends AbstractController
{

    private $passwordEncoder;

public function __construct(UserPasswordEncoderInterface $passwordEncoder)
{
  $this->passwordEncoder = $passwordEncoder;
}
    
   /**
     * @Route("/login_check", name="login", methods={"POST"})
     * @param JWTEncoderInterface $JWTEncoder
     * @throws \Lexik\Bundle\JWTAuthenticationBundle\Exception\JWTEncodeFailureException
     */
    public function log(Request $request, JWTEncoderInterface  $JWTEncoder)
    { 
   
       $values = json_decode($request->getContent());
        $username   = $values->username; 
        $password   = $values->password; 
            $repo = $this->getDoctrine()->getRepository(User::class);
            $user = $repo-> findOneBy(['username' => $username]);
            if(!$user){
                return $this->json([
                        'message' => 'Username incorrect'
                    ]);
            }

            $isValid = $this->passwordEncoder
            ->isPasswordValid($user, $password);
            if(!$isValid){ 
                return $this->json([
                    'message' => 'Mot de passe incorect'
                ]);
            }
            if($user->getStatut()=="bloquer"){
                return $this->json([
                    'message' => 'ACCÈS REFUSÉ vous ne pouvez pas connecter !'
                ]);
            }
            $token = $JWTEncoder->encode([
                'username' => $user->getUsername(),
                'exp' => time() + 86400 // 1 day expiration
            ]);

            return $this->json([
                'token' => $token
            ]);
    }
}

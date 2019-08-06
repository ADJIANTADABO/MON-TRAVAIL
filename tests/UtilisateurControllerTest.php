<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UtilisateurControllerTest extends WebTestCase
{
    public function testSomething()
    {
        $client = static::createClient([],[
                    'PHP_AUTH_USER'=>'admin',
                    'PHP_AUTH_PW'=>'123456'
                 ]);
                $crawler = $client->request('POST', '/api/utilisateur',[],[],
                ['CONTENT_TYPE' => 'application/json'],
                '{
                    "username":"admin1",
                    "password":"passer1",
                    "nom":"DIOP",
                    "prenom" :"Kya",
                    "adresse" :"Niarry Tally",
                    "email": "kya1@hotmail.com",
                    "telephone":7633932,
                    "cni" :7856356123,
                    "statut":"aCtif",
                    "partenaire":6,
                    "compte":4,
                    "profil":4
                   
        
                }'
            );
                $rep = $client->getResponse();
                 var_dump($rep);
                $this->assertSame(200, $client->getResponse()->getStatusCode());
        }
}

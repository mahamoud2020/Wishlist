<?php

require_once __DIR__ . '/vendor/autoload.php';
use wishlist\conf\ConnectionFactory as CF;
use wishlist\divers\Outils;
use wishlist\divers\Formulaire;

use wishlist\fonction\Authentification as AUTH;
use wishlist\fonction\Alerte;
use wishlist\fonction\FctnListe as FL;
use wishlist\fonction\FctnCagnotte as CG;
use wishlist\fonction\CreateurItem as CI;
use wishlist\fonction\GestionImage as GI;


// CONNECTION DU BDD
$cf = new CF();
$cf->setConfig('src/conf/conf.ini');
db = $cf->makeConnection();
$app = new \Slim\Slim();

//tu peu mettre l'accueil mahmoud

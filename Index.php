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

//accueil 
app->get('/', function () {
	Alerte::getErrorAlert( 'Aucune liste detecté');

	
	Alerte::clear();
	echo '<h4>soyez le bienevenu sur l/importance de liste de souhait.</h4>';
	Formulaire::rechercheListe();
	echo '<hr>';
	FL::displayAll();
});
$app->post('/search', function () {
	Outils::goTo('liste/'. $_POST['token'], 'Redirection vers la liste en cours..');
});

$app->get('/liste/:token', function($token) {
	Alerte::getSuccesAlert('item_added', "Objet ajouté à la liste");
	Alerte::getSuccesAlert('add_message', "Message ajouté à la liste.");
	FL::liste($token);
});

$app->get('/myliste', function() {
	FL::displayOwnListe();
});
$app->get('/saveliste', function() {
	FL::displaySaveListe();
});
$app->post('/saveliste-add', function() {
	FL::saveListe();
});

$app->post('/saveliste-remove/:token', function($token) {
	FL::unsaveListe($token);
});


//creation de la liste
$app->get('/add-liste-form', function() {
	Alerte::getErrorAlert('date_fault', 'Date saisie invalide');
	Alerte::getErrorAlert('list_exist', 'Une liste avec le nom identique existe déjà !');
	if(AUTH::isConnect()){
		Formulaire::ajouterListe();
		echo '<hr>';
	}

Formulaire::creeListe();
});
$app->post('/add-liste', function() {
	FL::cree();
});
$app->post('/add-user', function() {
	FL::ajoutUtilisateur();
});




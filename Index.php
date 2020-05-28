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

// LISTE

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


//creation d'une liste
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
//message à la liste
$app->post('/add-mess/:token', function($token) {
  FL::ajouterMessage($token);
});

//liste visible 
app->post('/liste-published/:id', function($token) {
  FL::publication($token);
});
$app->post('/edit-liste/:id', function($token) {
  FL::modifier($token);
});
// ITEME
app->get('/add-item-form', function() {
	Formulaire::ajoutItem();
});
$app->post('/add-item', function() {
	CI::itemAdd();
});
$app->get('/item/:name', function($item_name) {
	PI::displayItem($item_name);
});
// AjoutER une cagnotte pour un iteme
$app->post('/add-cagnotte/:name', function($iteme_name) {
	CG::addCagnotte($iteme_name);
});
// Définition d'une cagnote pour un objet quelconque
$app->get('/set-cagnotte/:name', function($iteme_name) {
	CG::setCagnotte($iteme_name);
});

// Reservation item 

$app->post('/reserver/:name', function($item_name) {
	$_SESSION['item_action'] = "reservation";
	PI::displayItem($item_name);
});

// Modification item

$app->post('/edit-item/:name', function($item_name) {
	$_SESSION['item_action'] = "editer";
	PI::displayItem($item_name);
});

// suppression item

$app->post('/delete-item/:name', function($item_name) {
	$_SESSION['item_action'] = "supprimer";
	PI::displayItem($item_name);
});

$app->post('/upload-image/:name', function($item_name) {
	$_SESSION['item_action'] = "uploadImage";
	PI::displayItem($item_name);
});

// Supprimer une image
$app->post('/delete-image/:name', function($item_name) {
	$_SESSION['item_action'] = "supprimerImage";
	PI::displayItem($item_name);
});

// Authenifier

$app->post('/connection', function() {
	if (isset($_POST['signin']))
		AUTH::Connection();
	else if (isset($_POST['signup']))
    	AUTH::Inscription();
});
// Deconnection
$app->get('/deconnection', function() {
	AUTH::Deconnection();
});

// Affichage du compte

$app->get('/compte', function() {
	PC::displayCompte();
});
$app->get('/auth-connexion', function() {
	Formulaire::connection();
});
$app->get('/auth-inscription', function() {
	Formulaire::inscription();
})
	
// modification compte
	
$app->post('/edit-compte', function () {
	$_SESSION['compte_action'] = "editer";
	PC::displayCompte();
});

$app->post('/change-password', function () {
	$_SESSION['compte_action'] = "change_password";
	PC::displayCompte();
});

$app->post('/delete-compte', function () {
	AUTH::deleteUser();
	Outils::goTo('index.php', 'Compte supprimé!');
});

$app->run();

Outils::footerHTML();


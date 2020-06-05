<?php

namespace wishlist\fonction;

use wishlist\divers\Formulaire;

use wishlist\fonction\Authentification as AUTH;
use wishlist\fonction\CreateurItem as CI;

use wishlist\modele\Iteme;
use wishlist\modele\Liste;
use wishlist\modele\Message;
use wishlist\modele\Reservation;
use wishlist\modele\Enreg_liste;
use wishlist\modele\Utilisateur;


class FctnListe {


	{

		if (!$_POST['titre']) {
			echo 'impossible de creer, le champ requis est vide.'; //alerte
			exit();
		}

		$test = Liste::where('titre', 'like', $_POST['titre'])->first();
    	if ($test) {
				Alerte::set('existing_list');
				Outils::goTo(Outils::getArbo().'add-liste-form', 'Une liste avec le même nom existe déjà !');
				exit();
    	}


		

	public static function publication($token)
	{
		$liste = Liste::where('jeton_prive', 'like', $token)
						 ->first();
		if ($liste->published == true)
		{
			$liste->published = false;
			$liste->save();
			Outils::goTo('../liste/'. $token, 'La liste devient privée.', 2);
		}
		else
		{
			$liste->published = true;
			$liste->save();
			Outils::goTo('../liste/'. $token, 'La liste a été rendu publique.', 2);
		}

	}

	public static function ajout_utilisateur()
	{
		if($_SESSION["wishlist_idUtilisateur"] != null)
		{
			if($_POST['token'] != null){
				$liste = Liste::where('jeton_prive', 'like', $_POST['token'])->first();
				if($liste)
				{
					if ($liste->user_id == $_SESSION["wishlist_idUtilisateur"]) {
						echo "Cette liste vous appartient déjà.";
					}
					else if($liste->user_id == null)
					{
						$liste->user_id = strip_tags($_SESSION["wishlist_idUtilisateur"]);
						$liste->save();
						echo 'La liste a bien été ajouter à votre compte';
					}
					else{
						echo "Cette liste appartient déjà à un autre utilisateur.";
					}
				}
				else {
					echo "Aucune liste correspond au token indiquer";
				}
			}
			else {
				echo "Aucun token introduit";
			}
		}
	}

	public static function modifie($token)
	{

		if (!isset($_SESSION['wishlist_liste_token']))
		{
			echo 'Token erroné';
		}
		if (!$_POST['titre'] && !$_POST['description'] && !$_POST['id_utilisateur'] && !$_POST['url'])
		{
			echo 'Aucunes modification effectué, pas de champs renseigné.';
		}
		$liste = SELF::getCurrentPrivateList();

		if (!$liste)
		{
			echo 'Aucune liste trouvé';
			exit();
		}
		echo "Modifications effectuées sur la liste " . $liste->titre;
		$liste->titre = strip_tags($_POST['titre']);
		$liste->description = strip_tags($_POST['description']);
		$liste->save();
	}


	public static function ajouter_message($token)
	{
		$liste = Liste::where('jeton_prive', 'like', $token)->orWhere('token_publique', 'like', $token)->first();
		$message = new Message;
		$message->no_liste=strip_tags($liste->no);
		$message->msg=strip_tags($_POST['message']);
		$message->save();
		Alerte::set('add_message');
		
	}



	public static function displayAll() {

		if(isset($_GET["token"])) {
			Outils::goTo('liste/'. $_GET['token'], 'Recherche en cours...', 1);
		}
		else {
			$_SESSION['wishlist_liste_token'] = null;
			$listes=Liste::where('published', 'like', '1')
				->orderBy('expiration', 'asc')
				->whereDate('expiration', '>', date('Y-m-d'))
				->get();
			echo "<h1>Listes de souhaits</h1>";
			if (sizeof($listes) == 0) {
				echo 'Aucune liste publique existante';
			}

			foreach ($listes as $liste) {
				echo "<li>";
				if(AUTH::isConnect() && $liste->utilisateur_id == $_SESSION['wishlist_idUtilisateur']) {
					echo '<a href="liste/' . $liste->jeton_prive . '">' . $liste->titre . '</a></br>';
				} else {
					echo '<a href="liste/' . $liste->token_publique . '">' . $liste->titre . '</a></br>';
				}
				echo "</li>";
			}
		}
	}

	public static function displayOwnListe() {
		if (!AUTH::isConnect()) {
			
		}

		$user = User::where('id', '=', $_SESSION['wishlist_userid'])->first();
		if (!$user->listes) {
			echo '<h4>Aucunes listes crée pour le moment</h4>';
		}

		foreach($user->listes as $liste) {
			
		}
	}

	
	
	public static function unsaveListeButton($token) {
		echo '
		<form action="saveliste-remove/' . $token . '" method="POST">
				<button type="submit" class="alert button">
					<div class ="row">
						<div class="columns small-2 fi-trash"></div>
						<div class="columns">Oublier la liste</div>
					</div>
				</button>
		</form>';
	}

	public static function unsaveListe($token) {
		if (!Outils::checkSession(array('wishlist_idUtilisateur'))) {
			Outils::goTo('../index.php', 'Redirection en cours..');
		}

		$liste = Liste::select('no')->where('token_publique', '=', $token)->first();
		if (!$liste) {
			Outils::goTo('../index.php', "Redirection en cours..");
		}

		$save_liste = Save_liste::where('id_utilisateur', '=', $_SESSION['wishlist_idUtilisateur'])
					->where('no_liste', '=', $liste->no)
					->first();

		if (!$save_liste) {
			Outils::goTo('../index.php', "Redirection en cours..");
		}

		$save_liste->delete();
		Alerte::set('liste_unsave');
		Outils::goTo('../saveliste', 'Redirection en cours..');
	}

	public static function affichageMsgListe($liste){
		$messlist=$liste->message;
		echo "<h2>Message : </h2>";
		foreach ($messlist as $message) {
			echo '- ' . $message->msg . '<br/>';
		}
	}

	public static function affichageItemListe($item){
		echo '<li>
				Nom de l\'objet : <a href="../item/'. $item->nom .'">'. $item->nom .'</a>
				<br/>Description : '. $item->descr . '<br/>
			</li>';
	}


	public static function getCurrentPrivateList(){
		$list = Liste::where('token_private', 'like', $_SESSION['wishlist_liste_token'])
			->first();
		return $list;
	}

	public static function getCurrentPublicList(){
		$list = Liste::where('token_publique', 'like', $_SESSION['wishlist_liste_token'])
			->first();
		return $list;
	}

	public static function returnBouton() {
		echo '<a href="../liste/' . $_SESSION['wishlist_liste_token'] . '" class="button">Retour à la liste</a>';
	}

	public static function boutonPublication(){
		$liste = SELF::getCurrentPrivateList();
		$token = $_SESSION['wishlist_liste_token'];
		if($liste->published == true) {
			echo '<form action="../liste-published/'. $token .'" method="post">
				<button class="button" type="submit">Rend la liste privée</button>
			</form>';
		} else {
			echo '<form action="../liste-published/'. $token .'" method="post">
				<button class="button" type="submit">Rend la liste publique</button>
			</form>';
		}
	}
}

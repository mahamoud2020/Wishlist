<?php

namespace wishlist\fonction;

use Cartalyst\Sentinel\Native\Facades\Sentinel;
use wishlist\modele\Utilisateur;


class Compte {

	public static function compte_details() {
		$user = Utilisateur::where('id', '=', $_SESSION['wishlist_idUtilisateur'])
			->first();

		echo '
		<div class= "utilis">
		<ul class="comptes">
			<li class="username">';
		echo '<strong>' . $utilisateur->email . '</strong>';
		echo '
			</li>
			<li>';
		if ($utilisateur->nom) echo 'Nom : ' . $utilisateur->nom;
		else echo 'Nom : non renseigné';
		echo '
			</li>
			<li>';
		if ($utilisateur->prenom) echo 'Prenom : ' . $utilisateur->prenom;
		else echo 'Prenom : non renseigné';
		echo '
			</li>
		</ul>
		</div>';
	}

	public static function compte_edit() {
		$utilisateur = Utilisateur::where('id', '=', $_SESSION['wishlist_idUtilisateur'])
			->first();

		if ($_POST['nom'] && $_POST['nom'] != '') $utilisateur->nom = strip_tags($_POST['nom']);
		if ($_POST['prenom'] && $_POST['prenom'] != '') $utilisateur->first_name = strip_tags($_POST['prenom']);
		$utilisateur->save();
	}

}

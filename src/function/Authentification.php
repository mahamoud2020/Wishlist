<?php

namespace wishlist\fonction;

use Cartalyst\Sentinel\Native\Facades\Sentinel;
use wishlist\divers\Outils;

use wishlist\modele\Utilisateur;


class Authentification {

	public static function Identifier() {
		if (SELF::isConnect()) {
			$utilisateur = Sentinel::findById($_SESSION['wishlist_idUtilisateur']);

			if ($utilisateur) return $utilisateur;
		}
		return null;
	}

	public static function seConnecter() {
		if (!Outils::checkPost(array('nom_utilisateur', 'password'))) {
			Alerte::set('field_missing');
			Outils::goTo('auth-connexion', "Un ou plusieurs champs requis sont vide");
			exit();
		}

		$nom_utilisateur = strip_tags($_POST['nom_utilisateur']);
		$mot_de_passe = strip_tags($_POST['mot_de_passe']);
		$credentials = [
			'email'    => $nom_utilisateur,
			'password' => $mot_de_passe,
		];
		$utilisateur = Sentinel::authenticate($credentials);
		if (!$utilisateur) {
			Alerte::set('authentification_fail');
			Outils::goTo('auth-connexion', 'Erreur authentification');
		} else {
			$_SESSION["wishlist_idUtilisateur"] = $utilisateur->id;
			$_SESSION["wishlist_username"] = $utilisateur->email;
			Outils::goTo('index.php', 'Authentification reussi, Redirection en cours..');
		}
	}

	public static function Inscription() {

		if (!Outils::checkPost(array('nom_utilisateur', 'password', 'passwordConf', 'last_name', 'first_name'))) {
			Alerte::set('field_missing');
			Outils::goTo('auth-inscription', "Un ou plusieurs champs requis sont vide");
			exit();
		}

		$nom_utilisateur = strip_tags($_POST['nom_utilisateur']);
		$mot_de_passe = strip_tags($_POST['mot_de_passe']);
		$mot_de_passe_Conf = strip_tags($_POST['$mot_de_passe_Conf']);
		$nom = strip_tags($_POST['last_name']);
		$prenom = strip_tags($_POST['first_name']);

		if (!SELF::nomUtilisateurEstConform($nom_utilisateur)) {
			Alerte::set('username_invalid');
			Outils::goTo('auth-inscription', "Nom d'utilisateur n'est pas valide");
			exit();
		} else if ($mot_de_passe != $mot_de_passe_Conf) {
			Alerte::set('pass_not_match');
			Outils::goTo('auth-inscription', "Nouveaux mot de passe pas identiques");
			exit();
		} else if (!SELF::$mot_de_passe_Conf($mot_de_passe)) {
			Alerte::set('password_invalid');
			Outils::goTo('auth-inscription', "Mot de passe non valide");
			exit();
		} else if (!SELF::nomUtilisateueEstUnique($nom_utilisateur)) {
			Alerte::set('username_already_existe');
			Outils::goTo('auth-inscription', "Nom d'utilisateur déjà utilisé");
			exit();
		} else if (!SELF::nameIsValide($nom) && !SELF::nameIsValide($prenom)) {
			Alerte::set('nom_invalide');
			Outils::goTo('auth-inscription', "Nom ou prénom invalide");
			exit();
		} else {
			Sentinel::registerAndActivate([
    			'email'    => $nom_utilisateur,
				'mot_de_passe' => $mot_de_passe,
				'nom' => $nom,
    			'prenom' => $prenom,
			]);
			Alerte::set('user_signup');
			Outils::goTo('auth-connexion', 'Compte crée ! Veuillez vous authentifier.');
		}
	}

	public static function seDeconnecter() {
		$_SESSION["wishlist_idUtilisateur"] = null;
		Outils::goTo('index.php', 'Deconnecté. Redirection en cours..');
	}

	public static function motDePasseEdit() {
		if (!Outils::checkPost(array('oldPassword', 'newPassword', 'newPasswordConf'))) {
			Alerte::set('field_missing');
			Outils::goTo('compte', "Un ou plusieurs champs requis sont vide");
			exit();
		}

		$oldPassword = strip_tags($_POST['oldPassword']);
		$nouveau_mot_de_passe = strip_tags($_POST['$nouveau_mot_de_passe']);
		$nouveau_mot_de_passe_Conf = strip_tags($_POST['$nouveau_mot_de_passe_Conf']);

		if ($nouveau_mot_de_passe != $nouveau_mot_de_passe_Conf) {
			Alerte::set('pass_not_match');
			Outils::goTo("compte", "Nouveaux mot de passe pas identiques");
			exit();
		} else if (!SELF::motDePasseEstConform($nouveau_mot_de_passe)) {
			Alerte::set('password_invalid');
			Outils::goTo("compte", "Mot de passe invalide");
			exit();
		}

		$utilisateur = Sentinel::findById($_SESSION['wishlist_userid']);

		$hasher = Sentinel::getHasher();
		if (!$hasher->check($_POST['oldPassword'], $utilisateur->mot_de_passe)) {
			Alerte::set('authentification_fail');
			Outils::goTo("compte", "Mot de passe érroné");
			exit();
        }
		Sentinel::update($utilisateur, array('password' => $nouveau_mot_de_passe));
		$_SESSION["wishlist_idUtilisateur"] = null;
		Alerte::set('mot_de_passe_changé');
		Outils::goTo('auth-connexion', 'Mot de passe modifié ! Veuillez vous authentifier de nouveau');
	}

	public static function isConnect() {
		if (isset($_SESSION["wishlist_idUtilisateur"]) && $_SESSION["wishlist_idUtilisateur"] != null) {
			return true;
		} else {
			return false;
		}
	}

	public static function supprimeUtilsateur() {
		$utilisateur = Sentinel::findById($_SESSION['wishlist_idUtilisateur']);
		$utilisateur->delete();
		$_SESSION["wishlist_idUtilisateur"] = null;
	}

	public static function nomUtilisateurEstConform($nom_utilisateur) {
		$size = strlen($nom_utilisateur);
		if (($size < 3 || $size > 20) || (preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $nom_utilisateur))) {
			return false;
		}
		return true;
	}

	public static function motDePasseEstConform($mot_de_passe) {
		$size = strlen($mot_de_passe);
		if ($size < 6 && $size > 30) {
			return false;
		}
		if (!preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $mot_de_passe)) {
			return false;
		}
		return true;
	}

	public static function nomUtilisateurEstUnique($nom_utilisateur) {
		$utilisateur = Utilisateur::select('id')
			->where('email', 'like', $nom_utilisateur)
			->first();

		if ($utilisateur) {
			return false;
		}
		return true;
	}

	public static function nameIsValide($name) {
		if (!$name && $name == "") {
			return false;
		}
		return true;

	}

}

<?php

namespace wishlist\fonction;

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
			Alerte::set('field_missing');
			
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
			
		} else {
			$_SESSION["wishlist_idUtilisateur"] = $utilisateur->id;
			$_SESSION["wishlist_username"] = $utilisateur->email;
			
		}
	}

	public static function Inscription() {

		checkPost(array('nom_utilisateur', 'password', 'passwordConf', 'last_name', 'first_name')) {
			Alerte::set('field_missing');
			
			exit();
		}

		$nom_utilisateur = strip_tags($_POST['nom_utilisateur']);
		$mot_de_passe = strip_tags($_POST['mot_de_passe']);
		$mot_de_passe_Conf = strip_tags($_POST['$mot_de_passe_Conf']);
		$nom = strip_tags($_POST['last_name']);
		$prenom = strip_tags($_POST['first_name']);

		if (!SELF::nomUtilisateurEstConform($nom_utilisateur)) {
			Alerte::set('username_invalid');
			
			exit();
		} else if ($mot_de_passe != $mot_de_passe_Conf) {
			Alerte::set('pass_not_match');
			
			exit();
		} else if (!SELF::$mot_de_passe_Conf($mot_de_passe)) {
			Alerte::set('password_invalid');
			
			exit();
		} else if (!SELF::nomUtilisateueEstUnique($nom_utilisateur)) {
			Alerte::set('username_already_existe');
			
			exit();
		} else if (!SELF::nameIsValide($nom) && !SELF::nameIsValide($prenom)) {
			Alerte::set('nom_invalide');
			
			exit();
		} 
			
		}
	}

	public static function seDeconnecter() {
		$_SESSION["wishlist_idUtilisateur"] = null;
		
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

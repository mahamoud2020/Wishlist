<?php

namespace wishlist\fonction;

use wishlist\fonction\Alerte;
use wishlist\modele\Iteme;
use wishlist\modele\Liste;


class CreateurIteme {

	public static function itemDetails($item) {
		echo '
		<div class= "create">
			<div class="creat-img">';

		if ($iteme->img) {
			echo'
				<div class="pic-image">
					<img src="../' . $iteme->img .'">
				</div>';
		}
		echo '
			<div class="sun">
				<h3 class="titre-arti">' . $iteme->nom . '</h3>
				<p class="arti">' . $item->descr . '</p>
				<p class="price">Prix : ' . $iteme->tarif . '€</p>
			</div>
			<div class="expi">';
			if ( $iteme->reservation == 0) {
				echo 'Non reservé';
			}
			else if ($iteme->cagnotte == 0){
				echo 'Reservé par '. $iteme->participant_name;
				if($iteme->mesage){
					echo ' le message '. $iteme->message;
				}
			}
			else if ($iteme->cagnotte == 1){
				echo 'Reservation par cagnotte
							<ul>';
				$contribution = $iteme->cagnottes;
				foreach ($contribution as $key) {
					echo '<li>'.$key->name. ' a contribué à une hauteur de '. $key->montant . '€';
				}
				echo '</ul>';
			}

		} else {
			echo '<p>attendez l\'expiration de la liste</p>';
		}
		echo '
				</div>
			</div>
		</div>';
	}


	public static function iteme_ajouter() {
		if (SELF::item_verifier()) {
			$liste = Liste::select('no')
					->where('Jeton_prive', 'like', $_SESSION['wishlist_liste_token'])
					->first();

			$iteme = new Iteme();
			$iteme->liste_id = $liste->no;
			$iteme->nom = strip_tags($_POST['nom']);
			$iteme->descr = strip_tags($_POST['description']);
			$iteme->tarif = strip_tags($_POST['tarif']);
			$iteme->token_private = Outils::generateToken();
			$iteme->save();
			Alerte::set('iteme ajouté');
		}
		
	}

	public static function itemEdit($iteme) {
		if ($_POST['nom'] && $_POST['nom'] != '') $iteme->nom = strip_tags($_POST['nom']);
		if ($_POST['descr'] && $_POST['description'] != '') $iteme->description = strip_tags($_POST['description']);
		if ($_POST['tarif'] && $_POST['tarification'] != '') $iteme->tarification = strip_tags($_POST['tarification']);
		if ($_POST['url'] && $_POST['url'] != '') $iteme->url = strip_tags($_POST['url']);
		$item->save();
	}

	public static function iteme_supprimer($iteme) {
		$iteme->delete();
		
		exit();
	}

	public static function iteme_verifier() {

		if (!$_POST['nom'] || !$_POST['description'] || !$_POST['tarification']) {
			Alerte::set('vide');
			return false;
		}


		$liste = Liste::select('no')
				->where('Jeton_prive', 'like', $_SESSION['wishlist_liste_token'])
				->first();
		if (!$liste) {
			Alerte::set('liste non trouvé');
			return false;
		}


		$itemeTest = Iteme::where('nom', 'like', $_POST['nom'])
				->where('liste_id', "=", $liste->no)
				->first();
    	if ($itemeTest) {
			Alerte::set('ça existe');
        	return false;
    	}

		if (!numeric_test($_POST['tarif'])) {
			Alerte::set('prix non valide');
			return false;
		}
		return true;
	}

}

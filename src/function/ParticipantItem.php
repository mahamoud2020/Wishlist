<?php

namespace wishlist\fonction;

use wishlist\modele\Utilisateur;
use wishlist\modele\Iteme;
use wishlist\modele\Liste;
use wishlist\modele\Reservation;

use wishlist\fonction\Alerte;
use wishlist\fonction\FctnCagnotte as CG;


class ParticipantItem {

	public static function itemDetails ($iteme) {
		if ($iteme->reservation == 0) $reservation_state = 'non';
		else $reservation_state = 'oui';

		echo '
		<div class= "cagn">
			<div class="card-flex-article card">';
			Alerte::getSuccesAlert("item_reserve", "Item reservé !");

		if ($iteme->img) {
			echo'
				<div class="card-image">
					<img src="../' . $iteme->img .'">
				</div>';
		}

		echo '
				<div class="card-section">
					<h3 class="article-title">' . $iteme->nom . '</h3>
					<p class="article-summary">' . $iteme->descr . '</p>
					<p class="article-summary">Prix : ' . $iteme->tarif . '€</p>
				</div>

				<div class="card-divider align-middle">
					<br/>Reservé : ' . $reservation_state . '
				</div>
			</div>
		</div>';

		$liste = Liste::select('expiration')
				->where('token_publique', '=', $_SESSION['wishlist_liste_token'])
				->first();
		if (!Outils::listeExpiration($liste->expiration)) {
			if($iteme->reservation == 0 && $iteme->cagnotte == 0) FORM::itemReserve($iteme->nom);
			else if ($iteme->reservation == 0 && $iteme->cagnotte == 1) {
				CG::addCagnotteForm($item->nom);
			}
		}

	}

	public static function itemReserve ($iteme)
	{
		$iteme->reservation = 1;
		$iteme->participant_name = strip_tags($_POST['name']);
		$iteme->message = strip_tags($_POST['message']);
		$iteme->save();
		Alerte::set('item_reserve');
	}

}

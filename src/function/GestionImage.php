<?php

namespace wishlist\fonction;

use wishlist\modele\Iteme;


class GestionImage {

	public static function image_telecharge($iteme)
	{
		if (SELF::imageVerify($_FILES)) {
			if ($item->img) unlink($item->img);
			if (!is_dir('img/')) mkdir('img/');

			$nom = "img/$item->id-icone.png";
			$resultat = move_uploaded_file($_FILES['icone']['tmp_name'],$nom);
			if ($resultat) echo "Transfert réussi";

			$iteme->img = $nom;
			$iteme->save();
		}
	}

	public static function image_supprime($iteme)
	{
		if ($iteme->img) unlink($item->img);
		$iteme->img = NULL;
		$iteme->save();
	}

	public static function image_Verifie($file)
	{
		if ($_FILES['icone']['error'] > 0) {
			Alerte::set('transfer_error');
			return false;
		}

		if ($_FILES['icone']['size'] > 10000000) {
			Alerte::set('max_file_size');
			return false;
		}

		$extensions_valides = array( 'jpg' , 'jpeg' , 'gif' , 'png' );
		$extension_upload = strtolower(  substr(  strrchr($_FILES['icone']['name'], '.')  ,1)  );
		if ( !in_array($extension_upload, $extensions_valides) ) {
			Alerte::set('extension invalide');
			return false;
		}

		return true;
	}

}

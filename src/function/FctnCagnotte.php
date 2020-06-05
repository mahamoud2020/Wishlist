<?php

namespace wishlist\fonction;

use wishlist\modele\Iteme;
use wishlist\modele\Liste;
use wishlist\modele\Cagnotte;

use wishlist\fonction\FctnListe as FL;


class FctnCagnotte{

  public static function set_Cagnotte($nomIteme){
    $liste = FL::getCurrentPrivateList();
    $iteme = Iteme::where('nom', 'like', $item_name)->where('liste_id', '=', $liste->no)->first();


    if($iteme->cagnotte == 0) {
      $iteme->cagnotte = 1;
      $iteme->save();
      echo 'Cagnotte crée pour '. $nomIteme.' ! </br>';
    }
    else {
      echo "Cagnotte déjà crée pour cette item </br>";
    }
    echo '<a href="/MyWishList/iteme/' . $nomIteme .'">Retour à l`item</a>';
  }

  public static function ajout_Cagnotte_form($nomIteme){
    $liste = FL::getCurrentPublicList();
    $iteme = Iteme::where('nom', 'like', $nomIteme)->where('liste_id', '=', $liste->no)->first();
    $cagnotte = Cagnotte::where('item_id', '=', $item->id)->first();

    if(SELF::calcul_prix_restant($iteme) > 0)
    {
      echo '<h3>Ajout participation</h3>
            <form action="../add-cagnotte/' . $nomIteme . '" method="post">';
      if($cagnotte){
        echo $iteme->tarif - SELF::calcul_prix_restant($iteme) . '€ on a deja , il reste : '. SELF::calculPrixRestant($item). '€ à payer.';
      }
      if(!isset($_SESSION['wishlist_idUtilisateur']))
        echo '<p><input type="text" name="name" placeholder="Nom" required/></p>';

      echo   '<p><input type="number" name="montant" max="'.SELF::calcul_prix_restant($iteme).'" placeholder="Montant..." required/></p>
              <p><textarea type="text" name="message" placeholder="Laissez votre message..."/></textarea></p>
              <p><input type="submit" name="Make a present"></p>
            </form>';
    }
    else {
      echo "La cagnotte est vide";
    }

  }

  public static function ajouter_cagnotte($nomIteme){
    $liste = FL::getCurrentPublicList();
    if($liste){
      $iteme = Iteme::where('nom', 'like', $nomIteme)->where('liste_id', '=', $liste->no)->first();
      $participation = Cagnotte::where('item_id', '=', $item->id)->where('name', 'like', $_POST['name'])->first();
      if(!$participation){
        $cagnotte = new Cagnotte();
        $cagnotte->item_id = $iteme->id;
        if(isset($_SESSION['wishlist_userid'])){
          $cagnotte->user_id = strip_tags($_SESSION['wishlist_idUtilisateur']);
          $utilisateur = Utilisateur::where('id', 'like', $_SESSION['wishlist_idUtilisateur']);
          $cagnotte->name = strip_tags($utilisateur->email);
        }
        else {
          $cagnotte->name = strip_tags($_POST['name']);
        }
        $cagnotte->montant = strip_tags($_POST['montant']);
        $cagnotte->message = strip_tags($_POST['message']);
        $cagnotte->save();
        echo 'Participation effectué !';
      }
      else
        echo "Participation déjà effectué ! </br>";
      echo '<a href="/MyWishList/item/' . $nomIteme .'">Retour à l`item</a>';
      if(SELF::calcul_prix_restant($iteme) == 0)
      {
        $iteme->reservation == 1;
        $iteme->save();
      }
    }
    else {
      echo "Erreur, veuillez ressayer !";
    }
  }

  public static function calcul_prix_restant($iteme){
    $participation = $iteme->cagnottes;
    $cotiser = 0;
    foreach ($participation as $cagn) {
      if($cagn->item_id == $iteme->id)
        $cotiser += $cagn->montant;
    }
    return $item->tarif - $cotiser;
  }


  public static function bouton_sup($nomIteme){
    echo '
      <div class= "cagn">
        <a class="button" href="/MyWishList/del-cagnotte/' . $nomIteme .'">Supprimer la cagnotte</a>
      </div>';
  }

  public static function bouton_Creer($nomIteme){
    echo '
      <div class= "cagn">
        <a class="button" href="/MyWishList/set-cagnotte/' . $nomIteme .'">Creation des cagnotte</a>
      </div>';
  }
}

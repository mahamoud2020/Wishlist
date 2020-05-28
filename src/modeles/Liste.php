?php

namespace wishlist\modele;

class Liste extends \Illuminate\Database\Eloquent\Model
{
    protected $table = 'liste';
    protected $primaryKey = 'numero';
    public $timestamps = false;

	public function utilisateurr() {
        return $this->belongsTo('wishlist\modele\Utilisateur' , 'id');
    }

    public function iteme() {
        return $this->hasMany('wishlist\modele\Iteme', 'liste_id');
    }

    public function message() {
        return $this->hasMany('wishlist\modele\Message', 'liste_numero');
    }
}

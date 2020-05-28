<?php

namespace wishlist\modele;

class Utilisateur extends \Illuminate\Database\Eloquent\Model
{
    protected $table = 'utilisateur';
    protected $primaryKey = 'id';
    public $timestamps = false;

	public function listes() {
        return $this->hasMany('wishlist\modele\Liste', 'utilisateur_id');
    }
}

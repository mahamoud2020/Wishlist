<?php

namespace wishlist\modele;

class Iteme extends \Illuminate\Database\Eloquent\Model
{
    protected $table = 'iteme';
    protected $primaryKey = 'id';
    public $timestamps = false;

    public function liste() {
        return $this->belongsTo('wishlist\modele\Liste' , 'liste_id');
    }

    public function Cagnottes() {
        return $this->hasMany('wishlist\modele\Cagnotte' , 'iteme_id');
    }

}

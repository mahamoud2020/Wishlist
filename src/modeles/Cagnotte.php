<?php

namespace wishlist\modele;

class Cagnotte extends \Illuminate\Database\Eloquent\Model
{
    protected $table = 'cagnotte';
    protected $primaryKey = 'id_participation';
    public $timestamps = false;

    public function reservation() {
        return $this->belongsTo('wishlist\modele\Reservation' , 'iteme_id');
    }

}

<?php

namespace wishlist\modele;


class Message extends \Illuminate\Database\Eloquent\Model
{
	
    protected $table = 'message';
    protected $primaryKey = 'id';
    public $timestamps = false;

    public function liste() {
        return $this->belongsTo('wishlist\modele\Liste' , 'id_message');
    }

	
}

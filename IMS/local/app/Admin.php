<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Admin extends Model {

	protected $table = 'admin';
	
	
	protected $guard = "admin";
	
	protected $fillable = [
      'email', 'password',
    ];

}

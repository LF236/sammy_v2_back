<?php
namespace App\Infraestructure\Persistence\User;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class EloquentUser extends Authenticatable {	
	use HasApiTokens;
	use  SoftDeletes;
	protected $table = 'users';

	protected $fillable = [
		'name',
		'email',
		'password',
		'verifiedAt',
		'type',
		'is_active',
	];

	protected $hidden = [
		'password',
	];
}

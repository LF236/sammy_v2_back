<?php
namespace App\Infraestructure\Persistence\UserRole;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EloquentUserRole extends Model {
	use SoftDeletes;
	protected $table = 'user_roles';

	protected $fillable = [
		'user_id',
		'role_id',
	];
}

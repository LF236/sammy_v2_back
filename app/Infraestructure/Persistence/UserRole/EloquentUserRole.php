<?php
namespace App\Infraestructure\Persistence\UserRole;
use Illuminate\Database\Eloquent\Model;
class EloquentUserRole extends Model {
	protected $table = 'user_roles';

	protected $fillable = [
		'user_id',
		'role_id',
	];
}

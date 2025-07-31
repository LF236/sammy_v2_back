<?php
namespace App\Infraestructure\Persistence\RolsPermission;

use Illuminate\Database\Eloquent\Model;

class EloquentRolPermission extends Model {
	protected $table = 'role_permission';
	protected $fillable = [
		'role_id',
		'permission_id',
	];
}

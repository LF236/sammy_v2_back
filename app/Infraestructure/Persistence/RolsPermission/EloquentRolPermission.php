<?php
namespace App\Infraestructure\Persistence\RolsPermission;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EloquentRolPermission extends Model {
	use SoftDeletes;
	protected $table = 'role_permission';
	protected $fillable = [
		'role_id',
		'permission_id',
	];
}

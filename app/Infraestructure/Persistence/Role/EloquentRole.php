<?php
namespace App\Infraestructure\Persistence\Role;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class EloquentRole extends Model {
	protected $table = 'roles';
	public $incrementing = false;
	protected $keyType = 'string';
	use SoftDeletes;

	protected $fillable = [
		'name',
		'description',
		'is_active',
		'key'
	];

	protected static function boot() {
		parent::boot();

		static::creating(function ($model) {
			if (empty($model->id)) {	
				$model->id = (string) Str::uuid();
			}
		});
	}
}

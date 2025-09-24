<?php
namespace App\Infraestructure\Persistence\Permission;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class EloquentPermission extends Model {
	protected $table = 'permissions';
	use SoftDeletes;
	public $incrementing = false;
    protected $keyType = 'string';
	
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

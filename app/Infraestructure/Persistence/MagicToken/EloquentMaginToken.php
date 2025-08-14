<?php

namespace App\Infraestructure\Persistence\MagicToken;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
class EloquentMaginToken extends Model {
	protected $table = 'magic_tokens';
	public $incrementing = false;
    protected $keyType = 'string';
	protected $fillable = [
		'user_id',
		'expires_at',
		'used',
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

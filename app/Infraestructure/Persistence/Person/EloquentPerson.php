<?php
namespace App\Infraestructure\Persistence\Person;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class EloquentPerson extends Model {
    protected $table = 'person';
    public $incrementing = false;
    protected $keyType = 'string';
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'names',
        'last_name',
        'second_last_name',
        'birth_date',
        'curp',
        'rfc',
        'sex'
    ];

    protected static function boot() {
        parent::boot();

        static::creating(function ($model) {
            if(empty($model->id)) {
                $model->id = (string) Str::uuid();
            }
        });
    }


}
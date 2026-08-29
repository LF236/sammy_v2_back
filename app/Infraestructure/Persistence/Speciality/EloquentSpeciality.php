<?php
namespace App\Infraestructure\Persistence\Speciality;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class EloquentSpeciality extends Model {
  protected $table = 'specialities_cat';
  public $incrementing = false;
  protected $keyType = 'string';
  use SoftDeletes;

  protected $fillable = [
    'code',
    'name',
    'description',
    'is_active'
  ];

  protected static function boot() {
    parent::boot();
    static::created(function ($model) {
      if(empty($model->id)) {
        $model->id = (string) Str::uuid();
      }
    });
  }
}

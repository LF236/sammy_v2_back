<?php
namespace App\Infraestructure\Persistence\PersonType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class EloquentPersonType extends Model {
  protected $table = 'person_types';
  public $incrementing = false;
  protected $keyType = 'string';
  // use SoftDeletes;

  protected $fillable = [
    'code',
    'name',
    'description',
    'is_active'
  ];

  protected static function boot() {
    parent::boot();
    static::creating(function ($model) {
      if(empty($model->id)) {
        $model ->id = (string) Str::uuid();
      }
    });
  }

}
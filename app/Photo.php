<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    protected $fillable = [
        'tracking_code',
        'color',
        'creation_date',
        'width',
        'height',
        'likes',
        'description',
        'thumbnail',
        'small',
        'regular',
        'full',
        'raw',
        'user_id',
        'object',
        'classified',
        'tags',
        'classified_date'
    ];

    public function getObjectAttribute($value)
    {
        return json_decode($value);
    }

    public function setObjectAttribute($value)
    {
        $this->attributes['object'] = json_encode($value);
    }

    public function getClassifiedAttribute($value)
    {
      return (bool)$value;
    }
}

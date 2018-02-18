<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Photo extends Model
{
    use Searchable;

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

    /**
     * Get the indexable data array for the model.
     *
     * @return array
     */
    public function toSearchableArray()
    {
        $array = $this->only('tags', 'description');

        return $array;
    }
}

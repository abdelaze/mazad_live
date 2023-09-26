<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Attribute extends Model
{
    use HasFactory, SoftDeletes, HasTranslations;
    protected $guarded = ['id'];
    public $translatable =   ['input_label'];
    public function category():BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function getOptionsAttribute($value)
    {
        return json_decode($value);
    }

    public function getOptionsArAttribute($value)
    {
        return json_decode($value);
    }

    public function getOptionsLabelAttribute($value)
    {
        return json_decode($value);
    }

    public function getOptionsLabelArAttribute($value)
    {
        return json_decode($value);
    }
}

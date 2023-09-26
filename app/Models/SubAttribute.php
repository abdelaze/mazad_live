<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SubAttribute extends Model
{
    use HasFactory, SoftDeletes, HasTranslations;
    protected $guarded      = ['id'];
    public $translatable    = ['input_label'];
    public function subcategory():BelongsTo
    {
        return $this->belongsTo(SubCategory::class);
    }
}

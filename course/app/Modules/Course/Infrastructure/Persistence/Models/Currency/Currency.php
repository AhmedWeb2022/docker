<?php

namespace App\Modules\Course\Infrastructure\Persistence\Models\Currency;

use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;

class Currency extends Model implements TranslatableContract
{
    use HasFactory;
    use Translatable;
    protected $fillable = [
        'organization_id',
        'code'
    ];
    public $translatedAttributes = ['title'];
    protected $translationForeignKey = 'currency_id';
    protected $table = 'currencies';



}

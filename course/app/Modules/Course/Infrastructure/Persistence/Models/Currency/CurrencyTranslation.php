<?php

namespace App\Modules\Course\Infrastructure\Persistence\Models\Currency;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CurrencyTranslation extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'currency_translations';
    protected $fillable = [
        'currency_id',
        'locale',
        'title',
    ];
}

<?php

namespace App\Modules\Course\Infrastructure\Persistence\Models\Partner;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PartnerTranslation extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'partner_translations';
    protected $fillable = [
        'partner_id',
        'locale',
        'title',
        'description',
    ];
}

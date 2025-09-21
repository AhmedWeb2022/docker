<?php

namespace App\Modules\Course\Infrastructure\Persistence\Models\Certificate;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CertificateTranslation extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'certificate_translations';
    protected $fillable = [
        'certificate_id',
        'locale',
        'title',
        'about',
        'requirements',
        'target_audience',
        'benefits',
    ];
}

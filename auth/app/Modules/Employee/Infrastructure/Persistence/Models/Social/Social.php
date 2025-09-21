<?php

namespace App\Modules\Employee\Infrastructure\Persistence\Models\Social;

// use Illuminate\Contracts\Auth\MustVerifyEmail;


use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;

class Social extends Model
{
    /** @use HasFactory<\Database\Factories\EmployeeFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */


    protected $fillable = [
        'socialable_id',
        'socialable_type',
        'facebook',
        'twitter',
        'instagram',
        'linkedin',
        'tiktok',
        'whatsapp',
    ];

    protected $table = 'socials';

    public function socialable()
    {
        return $this->morphTo();
    }
}

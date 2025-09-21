<?php

namespace App\Modules\Admin\Infrastructure\Persistence\Models\Admin;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Hash;
use Illuminate\Notifications\Notifiable;
use App\Modules\Admin\Domain\Entity\AuthAdminEntity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Modules\Base\Domain\Holders\AuthHolderInterface;
use App\Modules\Base\Domain\Services\Email\EmailNotification;
use App\Modules\Admin\Infrastructure\Persistence\Models\AdminDevice\AdminDevice;
use App\Modules\Admin\Infrastructure\Persistence\Repositories\Admin\AdminRepository;

class Admin extends Authenticatable implements AuthHolderInterface
{
    /** @use HasFactory<\Database\Factories\AdminFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */


    protected $fillable = [
        'name',
        'username',
        'phone',
        'email',
        'password',
        'avatar',
        'id_number',
        'id_type',
        'id_image',
        'status',
        'is_email_verified',
        'is_phone_verified',
        'email_verified_at',
        'phone_verified_at',
        'organization_id',
        'stage_id',
        'location_id',
        'wallet',
        'last_login',
        'last_os',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }


    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    }

    public function adminDevices()
    {
        return $this->hasMany(AdminDevice::class, 'admin_id');
    }

    public function getAuthEntity(): AuthAdminEntity
    {
        return new AuthAdminEntity(
            id: $this->id,
            name: $this->name,
            email: $this->email,
            phone: $this->phone,
            password: $this->password,
            status: $this->status,
            model: $this,
        );
    }

        /**
     * Send OTP to this user via email or WhatsApp.
     *
     * @param string $fieldType
     * @param UserRepository $userRepository
     * @param string|null $otp
     * @return void
     */
    public function sendOtp(string $fieldType, AdminRepository $adminRepository, ?string $otp = null): void
    {
        $otp = $otp ?? '123456'; // Or generate a dynamic one
        setCache("otp_{$this->$fieldType}", $otp, now()->addMinutes(5));

        if (checkCredentialEmail($this->$fieldType)) {
            $this->notify(new EmailNotification($otp));
        } else {
            $adminRepository->sendWhatsAppMessage($this->phone, "Your OTP is: $otp");
        }
    }
}

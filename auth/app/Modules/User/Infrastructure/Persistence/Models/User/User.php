<?php

namespace App\Modules\User\Infrastructure\Persistence\Models\User;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Hash;
use Illuminate\Notifications\Notifiable;
use App\Modules\User\Domain\Entity\AuthUserEntity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Modules\Base\Domain\Holders\AuthHolderInterface;
use App\Modules\Base\Domain\Services\Email\EmailNotification;
use App\Modules\User\Infrastructure\Persistence\Models\UserDevice\UserDevice;
use App\Modules\User\Infrastructure\Persistence\Repositories\User\UserRepository;

class User extends Authenticatable implements AuthHolderInterface
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
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
        'image',
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
        'nationality_id',
        'wallet',
        'last_login',
        'last_os',
        'phone_code',
        'is_blocked',
        'gender',
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

    public function getImageLinkAttribute(): ?string
    {
        return $this->image ? asset('storage/' . $this->image) : null;
    }

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    }

    public function userDevices()
    {
        return $this->hasMany(UserDevice::class, 'user_id');
    }

    public function getAuthEntity(): AuthUserEntity
    {
        return new AuthUserEntity(
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
    public function sendOtp(string $fieldType, UserRepository $userRepository, ?string $otp = null): void
    {
        $otp = $otp ?? '123456'; // Or generate a dynamic one
        setCache("otp_{$this->$fieldType}", $otp, now()->addMinutes(5));

        if (checkCredentialEmail($this->$fieldType)) {
            $this->notify(new EmailNotification($otp));
        } else {
            $userRepository->sendWhatsAppMessage($this->phone, "Your OTP is: $otp");
        }
    }
}

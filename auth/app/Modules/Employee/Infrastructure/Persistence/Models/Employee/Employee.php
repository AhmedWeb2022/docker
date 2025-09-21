<?php

namespace App\Modules\Employee\Infrastructure\Persistence\Models\Employee;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Hash;
use Illuminate\Notifications\Notifiable;
use Astrotomic\Translatable\Translatable;
use App\Modules\Base\Domain\Entity\AuthEntityAbstract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Modules\Base\Domain\Holders\AuthHolderInterface;
use App\Modules\Employee\Domain\Entity\AuthEmployeeEntity;
use App\Modules\Base\Domain\Services\Email\EmailNotification;
use App\Modules\Employee\Infrastructure\Persistence\Models\Social\Social;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use App\Modules\Employee\Infrastructure\Persistence\ApiService\StageApiService;
use App\Modules\Employee\Infrastructure\Persistence\ApiService\CourseApiService;
use App\Modules\Employee\Infrastructure\Persistence\Models\EmployeeDevice\EmployeeDevice;
use App\Modules\Employee\Infrastructure\Persistence\Repositories\Employee\EmployeeRepository;
use App\Modules\Employee\Infrastructure\Persistence\Models\EmployeeSubjectStage\EmployeeSubjectStage;

class Employee extends Authenticatable implements AuthHolderInterface, TranslatableContract
{
    /** @use HasFactory<\Database\Factories\EmployeeFactory> */
    use HasFactory, Notifiable, HasApiTokens, Translatable;

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
        'role',
        'image',
        'cover_image',
        'real_video',
    ];
    public $translatedAttributes = ['title', 'description'];
    protected $translationForeignKey = 'employee_id';
    protected $appends  = ["image_link", "cover_image_link", "real_video_link"];
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];
    protected $table = 'employees';
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




    public function getImageLinkAttribute()
    {
        return $this->image ? asset('storage/' . $this->image) : '';
    }

    public function getCoverImageLinkAttribute()
    {
        return $this->cover_image ? asset('storage/' . $this->cover_image) : '';
    }

    public function getRealVideoLinkAttribute()
    {
        return $this->real_video ? asset('storage/' . $this->real_video) : '';
    }
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    }

    public function employeeDevices()
    {
        return $this->hasMany(EmployeeDevice::class, 'employee_id');
    }

    public function getAuthEntity(): AuthEmployeeEntity
    {
        return new AuthEmployeeEntity(
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
    public function sendOtp(string $fieldType, EmployeeRepository $employeeRepository, ?string $otp = null): void
    {
        $otp = $otp ?? '123456'; // Or generate a dynamic one
        setCache("otp_{$this->$fieldType}", $otp, now()->addMinutes(5));

        if (checkCredentialEmail($this->$fieldType)) {
            $this->notify(new EmailNotification($otp));
        } else {
            $employeeRepository->sendWhatsAppMessage($this->phone, "Your OTP is: $otp");
        }
    }

    public function courses()
    {
        $courseApiService = new CourseApiService();
        return $courseApiService->fetchTeacherCourses($this->id);
    }

    public function hasCourse()
    {
        $courseApiService = new CourseApiService();
        // dd($this->id);
        return $courseApiService->checkTeacherHasCourse($this->id)['data'];
    }
    public function social()
    {
        return $this->morphOne(Social::class, 'socialable');
    }

    public function certificates()
    {
        $courseApiService = new CourseApiService();
        return $courseApiService->fetchTeacherCertificates($this->id);
    }
    public function subjectStagess()
    {
        $stageApiService = new StageApiService();
        $stageSubjectIds = $this->subjectStages()->pluck('subject_stage_id')->toArray();
        // dd($stageSubjectIds);
        // dd($stageApiService->fetchSubjectStages($stageSubjectIds));
        return $stageApiService->fetchSubjectStages($stageSubjectIds);
    }

    public function stages()
    {
        $stageApiService = new StageApiService();
        $stageSubjectIds = $this->subjectStages()->pluck('subject_stage_id')->toArray();
        return $stageApiService->fetchSubjectStageStages($stageSubjectIds);
    }

    public function subjectStages()
    {
        return $this->hasMany(EmployeeSubjectStage::class, 'employee_id', 'id');
    }

    public function userCount()
    {
        $courseApiService = new CourseApiService();
        // dd($this->id);
        $users = $courseApiService->userCount($this->id);

        return $users ? $users['data'] : 0;
    }
}

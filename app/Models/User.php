<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\CausesActivity;
use Spatie\Activitylog\Traits\LogsActivity;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes, LogsActivity, CausesActivity;

    public const AVATAR_FILE_PATHS = [
        'nogender' => 'assets/users/avatars/nogender/avatar-nogender.jpg',
        // 'avatar-male.jpg',
        // 'avatar-female.jpg',
        // 'avatar-boy.jpg',
        // 'avatar-girl.jpg',
        // 'avatar-man.jpg',
        // 'avatar-woman.jpg',
    ];

    protected $fillable = [
        'role_id',
        'avatar_source',
        'name',
        'nickname',
        'biography',
        'email',
        'password',
        'deleted_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->dontLogIfAttributesChangedOnly(['updated_at', 'remember_token'])
        ->dontSubmitEmptyLogs()

        ->logAll()
        ->logOnlyDirty();
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function getAvatarAttribute(): string
    {
        return url($this->avatar_source);
    }

    public function categories(): HasMany
    {
        return $this->hasMany(Category::class);
    }

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    public function fields(): HasMany
    {
        return $this->hasMany(Field::class);
    }

    public function getRoleNameAttribute(): string
    {
        return $this->role->name;
    }

    public function getPrimaryTextAttribute()
    {
        return $this->nickname;
    }
}

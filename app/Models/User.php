<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Enums\UserRole;
use App\Models\Traits\HasIdentities;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Staudenmeir\EloquentHasManyDeep\HasRelationships;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, HasIdentities, HasRelationships, Notifiable;

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'role' => UserRole::class,
    ];

    public function hasPassword()
    {
        return ! is_null($this->password);
    }

    public function isAdmin()
    {
        return $this->role == UserRole::Admin;
    }

    public function isManager()
    {
        return in_array($this->role, [UserRole::Admin, UserRole::Manager]);
    }

    public function isManagerOfTeam(Team $team)
    {
        $teamIds = $this->getTeams()->pluck('id')->all();

        return $this->isManager() && in_array($team->id, $teamIds);
    }

    public function isManagerOfEngineer(Engineer $engineer)
    {
        $teamIds = $this->getTeams()->pluck('id')->all();

        if ($this->isManager()) {
            foreach ($engineer->teams as $team) {
                if (in_array($team->id, $teamIds)) {
                    return true;
                }
            }
        }

        return false;
    }

    public function hasEngineer()
    {
        return ! is_null($this->engineer);
    }

    public function getTeams()
    {
        return Team::getForUser($this);
    }

    public function teams()
    {
        return $this->belongsToMany(Team::class)
            ->with('descendantsAndSelf')
            ->withPivot('role')
            ->as('member');
    }

    public function engineer()
    {
        return $this->hasOne(Engineer::class, 'email', 'email');
    }
}

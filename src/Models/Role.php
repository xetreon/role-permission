<?php

namespace Xetreon\RolePermission\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property string $name
 * @property string $code
 * @property int $status
 */
class Role extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = ['name', 'code', 'status'];


    /**
     * @return BelongsToMany
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_roles')->withTimestamps();
    }

    /**
     * @return HasMany
     */
    public function permissions(): HasMany
    {
        return $this->hasMany(RolePermission::class, 'role_id', 'id');
    }
}

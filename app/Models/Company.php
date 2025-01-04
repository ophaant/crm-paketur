<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends Model
{
    use SoftDeletes, HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
    ];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d',
        'updated_at' => 'datetime:Y-m-d',
    ];

    public function scopeFilterByIlikeName(Builder $query, ?string $name): void
    {
        $query->when($name, function ($query) use ($name) {
            $query->orWhere('name', 'ILIKE', '%'.$name.'%');
        });
    }

    public function scopeSortBy(Builder $query, ?string $sortBy, ?string $direction = 'asc'): void
    {
        if ($sortBy) {
            $query->orderBy($sortBy, $direction);
        }
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }
}

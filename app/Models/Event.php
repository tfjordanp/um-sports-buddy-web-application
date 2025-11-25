<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Event extends Model
{
    // ... other properties

    protected $casts = [
        'scheduled_date_time' => 'datetime',
    ];

    public function organizer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'organizer_id');
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class,'UserEventApplications');
    }

    public function sport(): BelongsTo
    {
        return $this->belongsTo(Sport::class);
    }
    
}

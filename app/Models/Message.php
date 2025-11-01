<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Message extends Model
{
    use HasFactory;

    protected $guarded = [];

    /**
     * ✅ ADD THIS PROPERTY.
     * This tells Laravel to always treat these attributes as Carbon date objects.
     */
    protected $casts = [
        'read_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'edited_at' => 'datetime',
    ];

    public function conversation(): BelongsTo
    {
        return $this->belongsTo(Conversation::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class); // The sender
    }

    // For Replies
    public function parent(): BelongsTo {
        return $this->belongsTo(Message::class, 'parent_id');
    }

    // For Reactions
    public function reactions(): HasMany {
        return $this->hasMany(Reaction::class);
    }
}

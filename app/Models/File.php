<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class File extends Model
{
    use HasFactory;

    protected $table = 'files';

    protected $fillable = [
        'file',
        'file_name',
        'file_exist',
        'user_id'
    ];

    protected $casts = [
        'created_at'    => 'datetime',
        'updated_at'    => 'datetime'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getFileUrlAttribute()
    {
        return env("APP_URL").'/storage/'.env("FILES_DIR").'/'.$this->file;
    }

}

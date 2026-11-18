<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SharedFile extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'original_name',
        'file_path',
        'file_size',
        'mime_type',
        'expires_at',
        'download_limit',
        'download_count',
    ];
    
    protected $casts = [
        'expires_at' => 'datetime',
    ];
}

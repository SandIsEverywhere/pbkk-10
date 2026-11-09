<?php

namespace App\Models;

use Database\Factories\SecurityScannerFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class SecurityScanner extends Model
{
    /** @use HasFactory<SecurityScannerFactory> */
    use HasFactory;

    protected $fillable = ['name', 'status'];

    public function toolExecutionLogs(): MorphMany
    {
        return $this->morphMany(ToolExecutionLog::class, 'loggable');
    }
}

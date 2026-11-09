<?php

namespace App\Models;

use Database\Factories\LogAnalyzerFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class LogAnalyzer extends Model
{
    /** @use HasFactory<LogAnalyzerFactory> */
    use HasFactory;

    protected $fillable = ['name', 'status'];

    public function toolExecutionLogs(): MorphMany
    {
        return $this->morphMany(ToolExecutionLog::class, 'loggable');
    }
}

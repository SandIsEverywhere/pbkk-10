<?php

namespace App\Models;

use Database\Factories\ToolExecutionLogFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class ToolExecutionLog extends Model
{
    /** @use HasFactory<ToolExecutionLogFactory> */
    use HasFactory;

    protected $fillable = ['tool_name', 'arguments', 'result', 'status', 'duration_ms'];

    protected $casts = [
        'arguments' => 'array',
        'result' => 'array',
        'duration_ms' => 'integer',
    ];

    public function loggable(): MorphTo
    {
        return $this->morphTo();
    }
}

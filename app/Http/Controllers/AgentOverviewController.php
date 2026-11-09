<?php

namespace App\Http\Controllers;

use App\Models\ToolExecutionLog;
use Illuminate\View\View;

class AgentOverviewController extends Controller
{
    public function index(): View
    {
        $logs = ToolExecutionLog::with('loggable')
            ->latest()
            ->limit(50)
            ->get()
            ->groupBy(fn ($log) => class_basename($log->loggable_type));

        return view('agents.index', compact('logs'));
    }
}

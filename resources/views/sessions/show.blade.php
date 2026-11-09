<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <a href="{{ route('sessions.index') }}" class="text-sm text-indigo-600 hover:text-indigo-900">&larr; {{ __('Back to Sessions') }}</a>
                </div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ $chatSession->title ?? __('Untitled session') }}
                </h2>
                <p class="text-xs text-gray-500 mt-0.5">
                    {{ __('Created at:') }} {{ $chatSession->created_at->format('Y-m-d H:i') }}
                </p>
            </div>
            <div>
                @if ($naive)
                    <a href="{{ route('sessions.show', $chatSession) }}" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        {{ __('Show optimized (eager loaded)') }}
                    </a>
                @else
                    <a href="{{ route('sessions.show', ['chatSession' => $chatSession, 'naive' => 1]) }}" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        {{ __('Show naive (trigger N+1 for Debugbar)') }}
                    </a>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if ($chatSession->chatMessages->isEmpty())
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 text-gray-500">
                    {{ __('No messages in this session.') }}
                </div>
            @else
                @foreach ($chatSession->chatMessages as $message)
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 space-y-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                @php
                                    $badgeColor = match ($message->role) {
                                        'user' => 'bg-blue-100 text-blue-800',
                                        'assistant' => 'bg-green-100 text-green-800',
                                        'system' => 'bg-gray-100 text-gray-800',
                                        'tool' => 'bg-amber-100 text-amber-800',
                                        default => 'bg-gray-100 text-gray-800',
                                    };
                                @endphp
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-medium {{ $badgeColor }}">
                                    {{ ucfirst($message->role) }}
                                </span>
                            </div>
                            <span class="text-xs text-gray-400">
                                {{ $message->created_at->format('Y-m-d H:i:s') }}
                            </span>
                        </div>

                        <div class="text-gray-900 text-sm whitespace-pre-wrap bg-gray-50 p-4 rounded-md border border-gray-100">{{ $message->content }}</div>

                        <!-- Tool Execution Logs -->
                        <div class="mt-4 border-t border-gray-100 pt-4">
                            <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">{{ __('Tool Execution Logs') }}</h3>
                            @if ($message->toolExecutionLogs->isEmpty())
                                <p class="text-xs text-gray-400 italic">{{ __('(no tool calls)') }}</p>
                            @else
                                <div class="space-y-3">
                                    @foreach ($message->toolExecutionLogs as $log)
                                        <div class="bg-gray-50 rounded-md p-3 border border-gray-200 text-xs space-y-2">
                                            <div class="flex items-center justify-between">
                                                <div class="flex items-center gap-2">
                                                    <span class="font-mono font-semibold text-indigo-600">{{ $log->tool_name }}</span>
                                                    @php
                                                        $statusColor = match ($log->status) {
                                                            'success' => 'bg-green-100 text-green-800',
                                                            'failed' => 'bg-red-100 text-red-800',
                                                            'pending' => 'bg-yellow-100 text-yellow-800',
                                                            default => 'bg-gray-100 text-gray-800',
                                                        };
                                                    @endphp
                                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-medium {{ $statusColor }}">
                                                        {{ $log->status }}
                                                    </span>
                                                </div>
                                                <span class="text-gray-500">
                                                    {{ $log->duration_ms }}ms
                                                </span>
                                            </div>

                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-2 mt-2">
                                                <div>
                                                    <span class="font-semibold text-gray-600 block mb-1">{{ __('Arguments:') }}</span>
                                                    <pre class="bg-white p-2 rounded border border-gray-200 overflow-x-auto text-[11px] font-mono text-gray-800">{{ json_encode($log->arguments, JSON_PRETTY_PRINT) }}</pre>
                                                </div>
                                                <div>
                                                    <span class="font-semibold text-gray-600 block mb-1">{{ __('Result:') }}</span>
                                                    <pre class="bg-white p-2 rounded border border-gray-200 overflow-x-auto text-[11px] font-mono text-gray-800">{{ json_encode($log->result, JSON_PRETTY_PRINT) }}</pre>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
</x-app-layout>

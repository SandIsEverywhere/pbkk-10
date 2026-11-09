<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Agent Tool Execution Logs') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Summary Card -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-3">{{ __('Log Summary by Model Type') }}</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                    @foreach ($logs as $type => $group)
                        <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                            <span class="text-xs font-semibold text-gray-600 block">{{ $type }}</span>
                            <span class="text-2xl font-bold text-indigo-600 mt-1 block">{{ $group->count() }} <span class="text-xs font-normal text-gray-500">{{ __('logs') }}</span></span>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Logs Grouped By Type -->
            @foreach ($logs as $type => $group)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 space-y-4">
                    <h3 class="text-lg font-medium text-gray-900 border-b pb-2">{{ $type }}</h3>
                    <div class="space-y-4">
                        @foreach ($group as $log)
                            <div class="bg-gray-50 rounded-md p-4 border border-gray-200 text-xs space-y-3">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <span class="font-mono font-semibold text-indigo-600 text-sm">{{ $log->tool_name }}</span>
                                        @php
                                            $statusColor = match ($log->status) {
                                                'success' => 'bg-green-100 text-green-800',
                                                'failed' => 'bg-red-100 text-red-800',
                                                'pending' => 'bg-yellow-100 text-yellow-800',
                                                default => 'bg-gray-100 text-gray-800',
                                            };
                                        @endphp
                                        <span class="px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColor }}">
                                            {{ $log->status }}
                                        </span>
                                    </div>
                                    <span class="text-gray-500 font-medium">
                                        {{ $log->duration_ms }}ms
                                    </span>
                                </div>

                                <div class="text-gray-600 flex items-center gap-1">
                                    <span class="font-semibold">{{ __('Parent Loggable:') }}</span>
                                    @if ($log->loggable_type === \App\Models\ChatMessage::class)
                                        <span class="font-mono text-gray-800">Message #{{ $log->loggable_id }}</span>
                                    @else
                                        <span class="font-mono text-gray-800">{{ $log->loggable->name ?? ('#' . $log->loggable_id) }}</span>
                                    @endif
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mt-2">
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
                </div>
            @endforeach
        </div>
    </div>
</x-app-layout>

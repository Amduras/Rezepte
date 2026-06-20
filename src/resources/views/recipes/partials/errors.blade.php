@if ($errors->any())
    <div class="mx-5 mb-4 p-4 bg-red-900/40 border border-red-500/50 rounded-lg">
        <div class="flex items-start gap-3">
            <svg class="w-5 h-5 text-red-400 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
            <div class="flex-1">
                <p class="text-red-300 font-semibold text-sm mb-2">
                    Bitte korrigiere folgende Fehler:
                </p>
                <ul class="list-disc list-inside text-red-300/90 text-sm space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
@endif

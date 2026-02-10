<div class="flex-1 flex flex-col">
    <!-- Header - Inside Livewire component for proper wire:model binding -->
    <header class="flex items-center justify-between px-4 py-2 border-b border-slate-700/50 bg-slate-800/50 backdrop-blur-sm sticky top-0 z-40">
        <div class="flex items-center gap-3">
            <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-emerald-400 to-cyan-500 flex items-center justify-center">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2" />
                </svg>
            </div>
            <h1 class="text-sm font-semibold text-slate-200">Sokoto State Passport Capture</h1>
        </div>
        
        <div class="flex items-center gap-3">
            <!-- LGA Selection -->
            <div class="flex items-center gap-2">
                <label class="text-xs font-medium text-slate-400">LGA:</label>
                <select wire:model.live="lga"
                        class="bg-slate-900/50 border border-slate-600/50 rounded-lg px-3 py-1.5 text-sm text-slate-200 
                               focus:outline-none focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500/50
                               transition-colors min-w-40">
                    <option value="">Select LGA</option>
                    @foreach($lgas as $lgaOption)
                        <option value="{{ $lgaOption }}">{{ $lgaOption }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Actions -->
            @if($captureCount > 0)
            <button wire:click="deleteAll"
                    wire:confirm="Are you sure you want to delete ALL {{ $captureCount }} records? This cannot be undone."
                    class="inline-flex items-center gap-1.5 px-2.5 py-1.5 text-xs font-medium rounded-lg
                           bg-red-500/20 text-red-400 hover:bg-red-500/30 transition-colors">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
                Clear All
            </button>
            @endif
            <a href="{{ route('export.xlsx') }}" 
               class="inline-flex items-center gap-1.5 px-2.5 py-1.5 text-xs font-medium rounded-lg
                      bg-emerald-500/20 text-emerald-400 hover:bg-emerald-500/30 transition-colors">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                CSV
            </a>
            <a href="{{ route('export.pdf') }}" 
               class="inline-flex items-center gap-1.5 px-2.5 py-1.5 text-xs font-medium rounded-lg
                      bg-rose-500/20 text-rose-400 hover:bg-rose-500/30 transition-colors">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                </svg>
                PDF
            </a>
            <span class="text-xs text-slate-400 px-2 py-1 bg-slate-800/50 rounded-lg">
                <span class="font-mono text-emerald-400">{{ $captureCount }}</span> records
            </span>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-1 overflow-auto p-4 space-y-4">
        <!-- Compact Capture Form -->
        <div class="bg-slate-800/50 rounded-xl border border-slate-700/50 p-4">
            <div class="flex items-center gap-3">
                <!-- Passport Data Input -->
                <div class="flex-1 relative">
                    <input type="text" 
                           wire:model="rawData"
                           wire:keydown.enter="capture"
                           x-ref="passportInput"
                           placeholder="Focus here and swipe passport..."
                           class="w-full bg-slate-900/50 border border-slate-600/50 rounded-lg px-4 py-2.5 text-sm text-slate-200 
                                  placeholder:text-slate-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500/50
                                  transition-colors font-mono"
                           autocomplete="off"
                           autofocus>
                    <div class="absolute right-3 top-1/2 -translate-y-1/2">
                        <div class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></div>
                    </div>
                </div>

                <!-- Capture Button -->
                <button wire:click="capture"
                        wire:loading.attr="disabled"
                        class="px-6 py-2.5 bg-gradient-to-r from-emerald-500 to-cyan-500 text-white font-medium rounded-lg
                               hover:from-emerald-600 hover:to-cyan-600 focus:outline-none focus:ring-2 focus:ring-emerald-500/50
                               transition-all disabled:opacity-50 disabled:cursor-not-allowed text-sm whitespace-nowrap">
                    <span wire:loading.remove>Capture</span>
                    <span wire:loading>...</span>
                </button>
            </div>
            <p class="text-xs text-slate-500 mt-2">Scanner auto-submits on Enter key • Current LGA: <span class="text-emerald-400 font-medium">{{ $lga ?: 'Not selected' }}</span></p>
        </div>

        <!-- Search Bar -->
        <div class="flex items-center gap-4">
            <div class="relative flex-1 max-w-sm">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <input type="text" 
                       wire:model.live.debounce.300ms="search"
                       placeholder="Search records..."
                       class="w-full pl-10 pr-4 py-2 bg-slate-800/50 border border-slate-700/50 rounded-lg text-sm text-slate-200
                              placeholder:text-slate-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500/50">
            </div>
        </div>

        <!-- Data Table -->
        <div class="bg-slate-800/30 rounded-xl border border-slate-700/50 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-slate-800/50">
                        <tr class="text-left text-xs font-medium text-slate-400 uppercase tracking-wider">
                            <th class="px-4 py-3">S/N</th>
                            <th class="px-4 py-3">Given Names</th>
                            <th class="px-4 py-3">Last Name</th>
                            <th class="px-4 py-3">Gender</th>
                            <th class="px-4 py-3">Date Of Birth</th>
                            <th class="px-4 py-3">LGA</th>
                            <th class="px-4 py-3">Nationality</th>
                            <th class="px-4 py-3">Document Number</th>
                            <th class="px-4 py-3">Document Expiry Date</th>
                            <th class="px-4 py-3 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-700/30">
                        @forelse($passports as $passport)
                        <tr class="hover:bg-slate-800/30 transition-colors">
                            <td class="px-4 py-3 text-slate-500">
                                {{ $passports->firstItem() + $loop->index }}
                            </td>
                            <td class="px-4 py-3 text-slate-300">{{ $passport->givennames }}</td>
                            <td class="px-4 py-3 font-medium text-slate-200">{{ $passport->lastname }}</td>
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium
                                             {{ $passport->gender === 'M' ? 'bg-blue-500/20 text-blue-400' : 'bg-pink-500/20 text-pink-400' }}">
                                    {{ $passport->gender }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-slate-300">{{ $passport->date_of_birth }}</td>
                            <td class="px-4 py-3 text-slate-400">{{ $passport->lga }}</td>
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-slate-700/50 text-slate-300">
                                    {{ $passport->nationality }}
                                </span>
                            </td>
                            <td class="px-4 py-3 font-mono text-emerald-400">{{ $passport->passport_number }}</td>
                            <td class="px-4 py-3 font-mono text-emerald-400">{{ $passport->expiry_date }}</td>
                            <td class="px-4 py-3 text-right">
                                <button wire:click="deletePassport({{ $passport->id }})"
                                        wire:confirm="Delete this record?"
                                        class="text-slate-500 hover:text-red-400 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="11" class="px-4 py-12 text-center">
                                <div class="text-slate-500">
                                    <svg class="w-12 h-12 mx-auto mb-3 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    @if($search)
                                        No records match your search
                                    @else
                                        No passport records yet
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($passports->hasPages())
            <div class="px-4 py-3 border-t border-slate-700/50 bg-slate-800/30">
                {{ $passports->links() }}
            </div>
            @endif
        </div>
    </main>
</div>

@script
<script>
    Livewire.on('focus-input', () => {
        setTimeout(() => {
            document.querySelector('[x-ref="passportInput"]')?.focus();
        }, 100);
    });
</script>
@endscript

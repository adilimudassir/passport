<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Passport Data Capture - Sokoto State' }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="h-full bg-slate-900 text-slate-100 font-sans antialiased select-none">
    <!-- Toast Notification Container (Zero Dependency) -->
    <div x-data="{ 
        toasts: [],
        add(type, message) {
            const id = Date.now();
            this.toasts.push({ id, type, message });
            setTimeout(() => this.remove(id), 3000);
        },
        remove(id) {
            this.toasts = this.toasts.filter(t => t.id !== id);
        }
    }" 
    x-on:toast.window="add($event.detail.type, $event.detail.message)"
    class="fixed top-4 right-4 z-50 space-y-2 pointer-events-none">
        <template x-for="toast in toasts" :key="toast.id">
            <div x-show="true" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-x-8"
                 x-transition:enter-end="opacity-100 translate-x-0"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-x-0"
                 x-transition:leave-end="opacity-0 translate-x-8"
                 :class="{
                     'bg-emerald-500': toast.type === 'success',
                     'bg-red-500': toast.type === 'error',
                     'bg-amber-500': toast.type === 'warning',
                     'bg-blue-500': toast.type === 'info'
                 }"
                 class="flex items-center gap-3 px-4 py-3 rounded-lg text-white text-sm shadow-lg backdrop-blur-sm pointer-events-auto">
                <template x-if="toast.type === 'success'">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </template>
                <template x-if="toast.type === 'error'">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </template>
                <span x-text="toast.message"></span>
                <button @click="remove(toast.id)" class="ml-auto hover:opacity-70 transition-opacity">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </template>
    </div>

    <!-- Main Content - Livewire component handles its own header -->
    <div class="min-h-full flex flex-col">
        {{ $slot }}

        <!-- Footer -->
        <footer class="px-4 py-2 border-t border-slate-700/50 bg-slate-800/30 text-center">
            <span class="text-xs text-slate-500">Version 1.0</span>
        </footer>
    </div>

    @livewireScripts
</body>
</html>

<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Application Error</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />
    @vite(['resources/css/app.css'])
</head>
<body class="min-h-full bg-slate-950 text-slate-100 font-sans antialiased">
    @php
        $details = implode(PHP_EOL, [
            'Status: '.$status,
            'Type: '.$exception::class,
            'Message: '.$exception->getMessage(),
            'File: '.$exception->getFile(),
            'Line: '.$exception->getLine(),
            'Time: '.now()->format('d/m/Y H:i:s'),
            'Log File: '.$logPath,
            '',
            'Stack Trace:',
            $exception->getTraceAsString(),
        ]);
    @endphp

    <main class="mx-auto flex min-h-screen w-full max-w-6xl flex-col px-6 py-8">
        <div class="mb-6 flex items-start justify-between gap-4">
            <div>
                <p class="text-sm font-medium uppercase tracking-[0.2em] text-red-400">Application Error</p>
                <h1 class="mt-2 text-3xl font-semibold text-white">The Windows app hit an internal error.</h1>
                <p class="mt-3 max-w-3xl text-sm leading-6 text-slate-400">
                    This screen is shown only inside the native desktop app so you can see the real cause instead of a generic 500 page.
                </p>
            </div>
            <div class="rounded-lg border border-red-500/30 bg-red-500/10 px-4 py-3 text-right">
                <p class="text-xs uppercase tracking-wide text-red-300">HTTP Status</p>
                <p class="mt-1 text-2xl font-semibold text-red-200">{{ $status }}</p>
            </div>
        </div>

        <section class="grid gap-4 lg:grid-cols-[minmax(0,1fr)_320px]">
            <div class="rounded-xl border border-slate-800 bg-slate-900/80 p-5 shadow-2xl">
                <p class="text-xs uppercase tracking-wide text-slate-500">Message</p>
                <p class="mt-2 text-lg font-medium text-red-200">{{ $exception->getMessage() ?: 'No exception message available.' }}</p>

                <div class="mt-5 grid gap-4 sm:grid-cols-2">
                    <div class="rounded-lg border border-slate-800 bg-slate-950/70 p-4">
                        <p class="text-xs uppercase tracking-wide text-slate-500">Exception Type</p>
                        <p class="mt-2 break-all font-mono text-sm text-slate-200">{{ $exception::class }}</p>
                    </div>
                    <div class="rounded-lg border border-slate-800 bg-slate-950/70 p-4">
                        <p class="text-xs uppercase tracking-wide text-slate-500">Line</p>
                        <p class="mt-2 font-mono text-sm text-slate-200">{{ $exception->getLine() }}</p>
                    </div>
                </div>

                <div class="mt-4 rounded-lg border border-slate-800 bg-slate-950/70 p-4">
                    <p class="text-xs uppercase tracking-wide text-slate-500">File</p>
                    <p class="mt-2 break-all font-mono text-sm text-slate-200">{{ $exception->getFile() }}</p>
                </div>

                <div class="mt-4 rounded-lg border border-slate-800 bg-slate-950/70 p-4">
                    <div class="flex items-center justify-between gap-4">
                        <p class="text-xs uppercase tracking-wide text-slate-500">Stack Trace</p>
                        <button
                            type="button"
                            onclick="copyErrorDetails()"
                            class="inline-flex items-center rounded-lg border border-slate-700 bg-slate-900 px-3 py-1.5 text-xs font-medium text-slate-200 transition-colors hover:border-cyan-500/60 hover:text-cyan-300"
                        >
                            Copy Details
                        </button>
                    </div>
                    <pre id="error-details" class="mt-3 max-h-[28rem] overflow-auto rounded-lg bg-black/40 p-4 text-xs leading-6 text-slate-300">{{ $details }}</pre>
                </div>
            </div>

            <aside class="space-y-4">
                <div class="rounded-xl border border-slate-800 bg-slate-900/80 p-5">
                    <p class="text-xs uppercase tracking-wide text-slate-500">What To Check</p>
                    <ul class="mt-3 space-y-3 text-sm leading-6 text-slate-300">
                        <li>Look at the exception message first.</li>
                        <li>Use the file and line to jump straight to the failing code.</li>
                        <li>Check the log file if the stack trace references a previous error.</li>
                    </ul>
                </div>

                <div class="rounded-xl border border-slate-800 bg-slate-900/80 p-5">
                    <p class="text-xs uppercase tracking-wide text-slate-500">Log File</p>
                    <p class="mt-3 break-all font-mono text-xs leading-6 text-slate-300">{{ $logPath }}</p>
                </div>

                <div class="rounded-xl border border-slate-800 bg-slate-900/80 p-5">
                    <div class="flex flex-col gap-3">
                        <button
                            type="button"
                            onclick="window.location.reload()"
                            class="inline-flex items-center justify-center rounded-lg bg-cyan-500 px-4 py-2.5 text-sm font-medium text-slate-950 transition-colors hover:bg-cyan-400"
                        >
                            Reload App
                        </button>
                    </div>
                </div>
            </aside>
        </section>
    </main>

    <script>
        async function copyErrorDetails() {
            const content = document.getElementById('error-details')?.innerText ?? '';

            try {
                await navigator.clipboard.writeText(content);
            } catch (error) {
                console.error('Failed to copy error details', error);
            }
        }
    </script>
</body>
</html>

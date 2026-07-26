@php use Illuminate\Support\Facades\Crypt; @endphp
<!DOCTYPE html>
<html lang="en" class="dark">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <link rel="icon" href="{{ asset('assets/media/logos/unuja.png') }}" type="image/png" />
    <title>SiPermata - Log-Viewer</title>
    <meta name="description" content="SiPermata -  Universitas Nurul Jadid">
    <meta name="author" content="Universitas Nurul Jadid">
    <meta name="publisher" content="Pusat Data & Sistem Informasi Universitas Nurul Jadid">
    <meta name="language" content="Indonesian">
    <meta name="robots" content="noindex, nofollow, noarchive, nosnippet, noodp, noydir, nocache, notranslate">
    <meta name="googlebot" content="noindex, nofollow, noarchive, nosnippet, notranslate">
    <meta name="bingbot" content="noindex, nofollow, noarchive, nosnippet">
    <meta name="slurp" content="noindex, nofollow, noarchive, nosnippet">
    <meta name="duckduckbot" content="noindex, nofollow, noarchive, nosnippet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.6/css/dataTables.tailwindcss.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        mono: ['ui-monospace', 'SFMono-Regular', 'Menlo', 'Monaco', 'Consolas', "Liberation Mono",
                            "Courier New", 'monospace'
                        ],
                    },
                    colors: {
                        brand: '#4f46e5',
                        'brand-hover': '#4338ca',
                        'brand-light': '#e0e7ff',
                        danger: '#ef4444',
                        warning: '#f59e0b',
                        info: '#3b82f6',
                        success: '#10b981',
                    },
                    boxShadow: {
                        'neon': '0 0 15px rgba(79, 70, 229, 0.5)',
                    }
                }
            }
        }
    </script>
    <style>
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        ::-webkit-scrollbar-track {
            background: transparent;
        }

        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }

        .dark ::-webkit-scrollbar-thumb {
            background: #475569;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        .dark ::-webkit-scrollbar-thumb:hover {
            background: #64748b;
        }

        .glass {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .dark .glass {
            background: rgba(15, 23, 42, 0.7);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.05);
        }

        .hover-lift {
            transition: transform 0.2s cubic-bezier(0.4, 0, 0.2, 1), box-shadow 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .hover-lift:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
        }

        table.dataTable.no-footer {
            border-bottom: none !important;
        }

        .dataTables_wrapper .dataTables_paginate {
            display: flex;
            gap: 0.25rem;
            align-items: center;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button {
            border-radius: 0.5rem !important;
            border: 1px solid transparent !important;
            padding: 0.375rem 0.75rem !important;
            font-size: 0.875rem !important;
            font-weight: 500 !important;
            color: #64748b !important;
            transition: all 0.2s !important;
        }

        .dark .dataTables_wrapper .dataTables_paginate .paginate_button {
            color: #94a3b8 !important;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button:hover:not(.current):not(.disabled) {
            background: #f1f5f9 !important;
            color: #0f172a !important;
            border-color: #e2e8f0 !important;
        }

        .dark .dataTables_wrapper .dataTables_paginate .paginate_button:hover:not(.current):not(.disabled) {
            background: #1e293b !important;
            color: #f8fafc !important;
            border-color: #334155 !important;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button.current {
            background: #4f46e5 !important;
            color: white !important;
            border-color: #4f46e5 !important;
            box-shadow: 0 4px 6px -1px rgba(79, 70, 229, 0.3) !important;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button.disabled {
            opacity: 0.5;
            cursor: not-allowed !important;
        }

        .dataTables_wrapper .dataTables_length select,
        .dataTables_wrapper .dataTables_filter input {
            border-radius: 0.5rem;
            border: 1px solid #e2e8f0;
            padding: 0.375rem 0.75rem;
            outline: none;
            transition: all 0.2s;
        }

        .dark .dataTables_wrapper .dataTables_length select,
        .dark .dataTables_wrapper .dataTables_filter input {
            background: #0f172a;
            border-color: #334155;
            color: #f1f5f9;
        }

        .dataTables_wrapper .dataTables_length select:focus,
        .dataTables_wrapper .dataTables_filter input:focus {
            border-color: #4f46e5;
            box-shadow: 0 0 0 2px rgba(79, 70, 229, 0.2);
        }
    </style>
</head>

<body
    class="bg-slate-50 dark:bg-[#0b1120] text-slate-800 dark:text-slate-200 transition-colors duration-300 font-sans antialiased selection:bg-brand selection:text-white">

    <div
        class="md:hidden glass border-b border-slate-200 dark:border-slate-800 p-4 flex items-center justify-between sticky top-0 z-40">
        <div class="flex items-center gap-3">
            <div
                class="w-8 h-8 rounded-lg bg-gradient-to-br from-brand to-purple-600 flex items-center justify-center text-white shadow-neon">
                <i class="fas fa-bolt text-sm"></i>
            </div>
            <h1 class="font-bold text-base leading-tight tracking-tight text-slate-900 dark:text-white">SiPermata</h1>
        </div>
        <div class="flex items-center gap-2">
            <button onclick="toggleTheme()"
                class="w-8 h-8 rounded-full flex items-center justify-center bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400">
                <i class="fas fa-moon hidden dark:inline-block"></i>
                <i class="fas fa-sun dark:hidden"></i>
            </button>
            <button onclick="toggleSidebar()"
                class="w-8 h-8 rounded-md flex items-center justify-center bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300">
                <i class="fas fa-bars"></i>
            </button>
        </div>
    </div>

    <div class="min-h-screen flex relative overflow-hidden">

        <div id="sidebarOverlay" onclick="toggleSidebar()"
            class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-40 hidden md:hidden transition-opacity opacity-0">
        </div>

        <aside id="sidebar"
            class="fixed inset-y-0 left-0 transform -translate-x-full md:relative md:translate-x-0 transition-transform duration-300 w-72 glass border-r border-slate-200 dark:border-slate-800 flex flex-col z-50 shrink-0 h-[100dvh] md:h-auto">

            <div class="p-6 border-b border-slate-200 dark:border-slate-800 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div
                        class="w-10 h-10 rounded-xl bg-gradient-to-br from-brand to-purple-600 flex items-center justify-center text-white shadow-neon">
                        <i class="fas fa-bolt text-lg"></i>
                    </div>
                    <div>
                        <h1 class="font-bold text-lg leading-tight tracking-tight text-slate-900 dark:text-white">
                            SiPermata</h1>
                        <p class="text-xs text-slate-500 dark:text-slate-400 font-medium tracking-wide uppercase">Log
                            Explorer</p>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <button onclick="toggleTheme()"
                        class="hidden md:flex w-9 h-9 rounded-full items-center justify-center bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 hover:text-brand dark:hover:text-brand hover:bg-slate-200 dark:hover:bg-slate-700 transition-all">
                        <i class="fas fa-moon hidden dark:inline-block"></i>
                        <i class="fas fa-sun dark:hidden"></i>
                    </button>
                    <button onclick="toggleSidebar()"
                        class="md:hidden w-9 h-9 rounded-full flex items-center justify-center bg-slate-100 dark:bg-slate-800 text-slate-500 hover:text-danger hover:bg-slate-200 transition-all">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>

            <div class="flex-1 overflow-y-auto p-4 space-y-2">
                <h3 class="text-xs font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-3 px-2">
                    Log Files</h3>

                @if (isset($folders))
                    @foreach ($folders as $folder)
                        <div class="group relative">
                            <div
                                class="flex items-center gap-3 p-3 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800 cursor-pointer transition-colors border border-transparent hover:border-slate-200 dark:hover:border-slate-700">
                                <div class="text-warning"><i class="fas fa-folder"></i></div>
                                <span class="text-sm font-medium truncate"
                                    title="{{ basename($folder) }}">{{ basename($folder) }}</span>
                            </div>
                            @if (count($folder['subfolders']) > 0)
                                <div
                                    class="ml-4 hidden group-hover:block absolute left-full top-0 z-50 min-w-[200px] glass shadow-xl rounded-xl p-2 border border-slate-200 dark:border-slate-700 ml-2">
                                    @foreach ($folder['subfolders'] as $sub)
                                        <a href="?l={{ Crypt::encrypt($sub) }}"
                                            class="flex items-center gap-2 p-2 text-sm font-medium hover:bg-slate-100 dark:hover:bg-slate-800 rounded-lg truncate transition-colors">
                                            <i class="fas fa-folder text-warning"></i>
                                            {{ basename($sub) }}
                                        </a>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @endforeach
                @endif

                @foreach ($files as $file)
                    <a href="?l={{ Crypt::encrypt($file) }}" @class([
                        'flex items-center gap-3 p-3 rounded-xl text-sm font-medium transition-all duration-200 border group',
                        'bg-brand text-white border-brand shadow-lg shadow-brand/30' =>
                            $current_file == $file,
                        'border-transparent text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white hover:border-slate-200 dark:hover:border-slate-700' =>
                            $current_file != $file,
                    ]) title="{{ $file }}">
                        <i @class([
                            'fas fa-file-alt transition-colors',
                            'text-brand-light' => $current_file == $file,
                            'text-slate-400 group-hover:text-brand' => $current_file != $file,
                        ])></i>
                        <span class="truncate">{{ basename($file) }}</span>
                    </a>
                @endforeach
            </div>
        </aside>

        <main class="flex-1 flex flex-col min-w-0 h-screen overflow-hidden relative">

            <div
                class="absolute top-0 left-0 w-full h-64 bg-gradient-to-b from-brand/10 to-transparent dark:from-brand/5 -z-10">
            </div>
            <div
                class="absolute top-[-10%] right-[-5%] w-[40%] h-[40%] rounded-full bg-brand/20 dark:bg-brand/10 blur-[120px] -z-10 pointer-events-none">
            </div>

            <div
                class="px-4 sm:px-8 py-4 sm:py-6 glass border-b border-slate-200 dark:border-slate-800 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 sticky top-0 z-10">
                <div class="min-w-0 flex-1">
                    @if ($current_file)
                        <h2 class="text-2xl font-bold text-slate-900 dark:text-white truncate flex items-center gap-3">
                            {{ basename($current_file) }}
                            <span
                                class="px-3 py-1 rounded-full bg-slate-100 dark:bg-slate-800 text-xs font-semibold text-slate-500 dark:text-slate-400 border border-slate-200 dark:border-slate-700 whitespace-nowrap">
                                {{ count($logs ?? []) }} entries
                            </span>
                        </h2>
                    @else
                        <h2 class="text-2xl font-bold text-slate-400">No file selected</h2>
                    @endif
                </div>

                <div class="flex flex-wrap gap-3">
                    @if ($current_file)
                        <button onclick="downloadLog()"
                            class="hover-lift flex items-center gap-2 px-4 py-2 text-sm font-medium bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-700 dark:text-slate-200 hover:text-brand dark:hover:text-brand hover:border-brand/30 dark:hover:border-brand/50 transition-all shadow-sm">
                            <i class="fas fa-cloud-download-alt text-lg"></i>
                            <span class="hidden sm:inline">Download</span>
                        </button>
                        <button onclick="cleanLog()"
                            class="hover-lift flex items-center gap-2 px-4 py-2 text-sm font-medium bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-700 dark:text-slate-200 hover:text-warning dark:hover:text-warning hover:border-warning/30 dark:hover:border-warning/50 transition-all shadow-sm">
                            <i class="fas fa-broom text-lg"></i>
                            <span class="hidden sm:inline">Clean</span>
                        </button>
                        <button onclick="deleteLog()"
                            class="hover-lift flex items-center gap-2 px-4 py-2 text-sm font-medium bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-danger hover:bg-danger/5 hover:border-danger/30 transition-all shadow-sm">
                            <i class="fas fa-trash-alt text-lg"></i>
                            <span class="hidden sm:inline">Delete</span>
                        </button>
                        @if (count($files) > 1)
                            <button onclick="deleteAllLogs()"
                                class="hover-lift flex items-center gap-2 px-4 py-2 text-sm font-medium bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-danger hover:bg-danger/5 hover:border-danger/30 transition-all shadow-sm">
                                <i class="fas fa-dumpster text-lg"></i>
                                <span class="hidden xl:inline">Delete All</span>
                            </button>
                        @endif
                    @endif
                </div>
            </div>

            <div class="flex-1 overflow-auto p-4 md:p-8">
                @if ($logs === null)
                    <div
                        class="glass p-8 rounded-2xl border border-danger/20 text-center shadow-lg max-w-lg mx-auto mt-10">
                        <div
                            class="w-16 h-16 bg-danger/10 text-danger rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-exclamation-triangle text-2xl"></i>
                        </div>
                        <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-2">File Too Large</h3>
                        <p class="text-slate-500 mb-6">This log file is over 50MB and cannot be safely displayed in the
                            browser.</p>
                        <a href="?dl={{ Crypt::encrypt($current_file) }}"
                            class="inline-flex items-center gap-2 px-6 py-3 bg-brand text-white font-medium rounded-xl hover:bg-brand-hover transition-all shadow-lg shadow-brand/30 hover:shadow-brand/50 hover:-translate-y-0.5">
                            <i class="fas fa-download"></i> Download File Instead
                        </a>
                    </div>
                @elseif(empty($logs))
                    <div
                        class="glass flex flex-col items-center justify-center h-full rounded-2xl border border-slate-200 dark:border-slate-800 border-dashed">
                        <div class="text-slate-300 dark:text-slate-700 mb-4 animate-bounce">
                            <i class="fas fa-clipboard-list text-6xl"></i>
                        </div>
                        <h3 class="text-xl font-medium text-slate-500 dark:text-slate-400">No logs to display</h3>
                        <p class="text-sm text-slate-400 dark:text-slate-500 mt-2 text-center max-w-sm">Everything
                            looks
                            clean. Select a file from the sidebar or generate some logs to see them here.</p>
                    </div>
                @else
                    <div
                        class="bg-white dark:bg-slate-900 rounded-2xl shadow-xl shadow-slate-200/50 dark:shadow-none border border-slate-200 dark:border-slate-800 overflow-hidden relative z-0">
                        <div class="p-4 sm:p-6 overflow-x-auto">
                            <table id="logTable"
                                class="w-full text-left border-collapse whitespace-nowrap lg:whitespace-normal">
                                <thead>
                                    <tr
                                        class="text-xs uppercase tracking-wider text-slate-500 dark:text-slate-400 border-b border-slate-200 dark:border-slate-700">
                                        <th class="pb-4 font-semibold w-28 text-center">Level</th>
                                        <th class="pb-4 font-semibold px-4 w-48">Time</th>
                                        <th class="pb-4 font-semibold px-4 w-40">Environment</th>
                                        <th class="pb-4 font-semibold px-4">Message</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/50 text-sm">
                                    @foreach ($logs as $key => $log)
                                        <tr
                                            class="group hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                                            <td class="py-4 align-top text-center">
                                                @php
                                                    $levelClass = strtolower($log['level_class']);
                                                    $bgClass = 'bg-slate-100 dark:bg-slate-800';
                                                    $textClass = 'text-slate-600 dark:text-slate-400';
                                                    $icon = 'fa-info-circle';

                                                    if ($levelClass == 'danger' || $levelClass == 'error') {
                                                        $bgClass =
                                                            'bg-danger/10 dark:bg-danger/20 border border-danger/20';
                                                        $textClass = 'text-danger dark:text-red-400';
                                                        $icon = 'fa-times-circle';
                                                    } elseif ($levelClass == 'warning') {
                                                        $bgClass =
                                                            'bg-warning/10 dark:bg-warning/20 border border-warning/20';
                                                        $textClass = 'text-warning dark:text-yellow-400';
                                                        $icon = 'fa-exclamation-triangle';
                                                    } elseif ($levelClass == 'info') {
                                                        $bgClass = 'bg-info/10 dark:bg-info/20 border border-info/20';
                                                        $textClass = 'text-info dark:text-blue-400';
                                                        $icon = 'fa-info-circle';
                                                    } elseif ($levelClass == 'success') {
                                                        $bgClass =
                                                            'bg-success/10 dark:bg-success/20 border border-success/20';
                                                        $textClass = 'text-success dark:text-emerald-400';
                                                        $icon = 'fa-check-circle';
                                                    }
                                                @endphp
                                                <div
                                                    class="inline-flex items-center justify-center gap-1.5 px-2.5 py-1.5 rounded-md text-[11px] font-bold uppercase tracking-wider {{ $bgClass }} {{ $textClass }} shadow-sm w-[100px]">
                                                    <i class="fas {{ $icon }}"></i>
                                                    {{ $log['level'] }}
                                                </div>
                                            </td>

                                            <td
                                                class="py-4 px-4 align-top text-slate-500 dark:text-slate-400 whitespace-nowrap font-medium">
                                                <div class="flex items-center gap-2">
                                                    <i class="far fa-clock opacity-50"></i>
                                                    {{ $log['date'] }}
                                                </div>
                                            </td>

                                            <td class="py-4 px-4 align-top">
                                                <span
                                                    class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-semibold bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 border border-slate-200 dark:border-slate-700">
                                                    {{ $log['context'] }}
                                                </span>
                                            </td>

                                            <td class="py-4 px-4 align-top">
                                                <div class="flex items-start justify-between gap-4">
                                                    <div class="max-h-[300px] overflow-y-auto w-full pr-2">
                                                        <div class="font-mono text-[13px] leading-relaxed text-slate-800 dark:text-slate-300 whitespace-pre-wrap break-all m-0">@php echo e(trim($log['text'])); @endphp</div>
                                                    </div>
                                                    @if ($log['stack'])
                                                        <button onclick="toggleStack('stack{{ $key }}')"
                                                            class="shrink-0 w-8 h-8 flex items-center justify-center rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-500 hover:text-brand hover:bg-brand/10 transition-colors border border-transparent hover:border-brand/20">
                                                            <i
                                                                class="fas fa-chevron-down transition-transform duration-300"></i>
                                                        </button>
                                                    @endif
                                                </div>

                                                @if ($log['stack'])
                                                    <div id="stack{{ $key }}"
                                                        class="hidden mt-4 overflow-hidden rounded-xl border border-slate-200 dark:border-slate-700/50 bg-slate-50 dark:bg-[#0f172a] shadow-inner">
                                                        <div
                                                            class="px-4 py-2.5 bg-slate-200/50 dark:bg-slate-800/80 border-b border-slate-200 dark:border-slate-700/50 flex items-center gap-2 text-xs font-bold text-slate-600 dark:text-slate-400 uppercase tracking-wider">
                                                            <i class="fas fa-layer-group text-brand"></i> Stack Trace
                                                        </div>
                                                        <div class="p-4 overflow-x-auto">
                                                            <pre class="font-mono text-[12px] leading-relaxed text-slate-600 dark:text-slate-400">{{ trim($log['stack']) }}</pre>
                                                        </div>
                                                    </div>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif
            </div>

            <div id="loading"
                class="absolute inset-0 bg-white/50 dark:bg-slate-900/50 backdrop-blur-sm flex items-center justify-center hidden z-50 transition-opacity">
                <div
                    class="bg-white dark:bg-slate-800 p-6 rounded-2xl shadow-2xl flex flex-col items-center gap-4 border border-slate-100 dark:border-slate-700 transform scale-100 animate-[pulse_2s_ease-in-out_infinite]">
                    <div class="w-12 h-12 border-4 border-brand/30 border-t-brand rounded-full animate-spin"></div>
                    <span
                        class="text-sm font-bold tracking-widest uppercase text-slate-600 dark:text-slate-300">Processing</span>
                </div>
            </div>
        </main>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>

    <script>
        $(document).ready(function() {
            if ($('#logTable').length) {
                $('#logTable').DataTable({
                    responsive: true,
                    ordering: false,
                    stateSave: true,
                    stateDuration: -1,
                    pageLength: 25,
                    columnDefs: [{
                            targets: 0,
                            className: 'text-center'
                        },
                        {
                            targets: [1, 2],
                            responsivePriority: 2
                        },
                        {
                            targets: 3,
                            responsivePriority: 1
                        }
                    ],
                    dom: '<"flex flex-col sm:flex-row items-center justify-between gap-4 mb-6"lf>rt<"flex flex-col sm:flex-row items-center justify-between gap-4 mt-6"ip>',
                    language: {
                        search: "",
                        searchPlaceholder: 'Search logs...',
                        lengthMenu: "Show _MENU_ entries",
                        info: "Showing _START_ to _END_ of _TOTAL_ entries",
                        paginate: {
                            previous: "<i class='fas fa-chevron-left text-sm'></i>",
                            next: "<i class='fas fa-chevron-right text-sm'></i>"
                        }
                    }
                });

                $('.dataTables_filter input').addClass('w-full sm:w-64');
            }
        });

        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');

            sidebar.classList.toggle('-translate-x-full');
            if (overlay.classList.contains('hidden')) {
                overlay.classList.remove('hidden');
                setTimeout(() => overlay.classList.remove('opacity-0'), 10);
            } else {
                overlay.classList.add('opacity-0');
                setTimeout(() => overlay.classList.add('hidden'), 300);
            }
        }

        function toggleTheme() {
            const html = document.documentElement;
            const isDark = html.classList.toggle('dark');
            localStorage.setItem('theme', isDark ? 'dark' : 'light');
        }

        (function initTheme() {
            const savedTheme = localStorage.getItem('theme') || 'light';
            document.documentElement.classList.toggle('dark', savedTheme === 'dark');
        })();

        function toggleStack(id) {
            const stack = document.getElementById(id);
            if (!stack) return;

            const btn = stack.previousElementSibling?.querySelector('button i');

            if (stack.classList.contains('hidden')) {
                stack.classList.remove('hidden');

                if (btn) btn.style.transform = 'rotate(180deg)';
            } else {
                stack.classList.add('hidden');

                if (btn) btn.style.transform = 'rotate(0deg)';
            }
        }

        const fileActions = {
            showLoading: () => {
                const loader = document.getElementById('loading');
                loader.classList.remove('hidden');
            },
            hideLoading: () => {
                document.getElementById('loading').classList.add('hidden');
            },
            downloadLog: () => {
                fileActions.showLoading();
                window.location.href =
                    `?dl={{ Crypt::encrypt($current_file) }}{{ isset($current_folder) && $current_folder ? '&f=' . Crypt::encrypt($current_folder) : '' }}`;
                setTimeout(fileActions.hideLoading, 1500);
            },
            cleanLog: () => {
                if (confirm('Are you sure you want to clean this log file?')) {
                    fileActions.showLoading();
                    window.location.href =
                        `?clean={{ Crypt::encrypt($current_file) }}{{ isset($current_folder) && $current_folder ? '&f=' . Crypt::encrypt($current_folder) : '' }}`;
                }
            },
            deleteLog: () => {
                if (confirm('Are you sure you want to delete this log file?')) {
                    fileActions.showLoading();
                    window.location.href =
                        `?del={{ Crypt::encrypt($current_file) }}{{ isset($current_folder) && $current_folder ? '&f=' . Crypt::encrypt($current_folder) : '' }}`;
                }
            },
            deleteAllLogs: () => {
                if (confirm('Are you sure you want to delete ALL log files?')) {
                    fileActions.showLoading();
                    window.location.href =
                        `?delall=true{{ isset($current_folder) && $current_folder ? '&f=' . Crypt::encrypt($current_folder) : '' }}`;
                }
            }
        };

        window.downloadLog = fileActions.downloadLog;
        window.cleanLog = fileActions.cleanLog;
        window.deleteLog = fileActions.deleteLog;
        window.deleteAllLogs = fileActions.deleteAllLogs;
    </script>
</body>

</html>

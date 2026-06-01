<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Illuminate\View\View;

class ErrorLogController extends Controller
{
    public function index(Request $request): View
    {
        abort_unless(auth()->user()->can('error_log-view'), 403);

        $page_title = 'Error Logs';

        $logPath = storage_path('logs/errors');

        if (! File::exists($logPath)) {
            File::makeDirectory($logPath, 0755, true);
        }

        $files = collect(File::files($logPath))
            ->filter(function ($file) {
                return str_starts_with($file->getFilename(), 'error-')
                    && str_ends_with($file->getFilename(), '.log');
            })
            ->sortByDesc(function ($file) {
                return $file->getMTime();
            })
            ->values();

        $logs = collect();

        foreach ($files as $file) {
            $logs = $logs->merge(
                $this->parseLogFile($file->getPathname(), $file->getFilename())
            );
        }

        $logs = $this->filterLogs($logs, $request);

        $logs = $logs
            ->sortByDesc(function ($log) {
                return strtotime($log->date_time);
            })
            ->values();

        $logs = $this->paginateLogs($logs, $request);

        $fileNames = $files
            ->map(function ($file) {
                return $file->getFilename();
            })
            ->values();

        return view('logs.error', compact(
            'page_title',
            'logs',
            'fileNames'
        ));
    }

    private function parseLogFile(string $path, string $fileName): Collection
    {
        $content = File::get($path);

        $entries = collect();

        /**
         * Expected log format:
         *
         * [2026-05-17 16:45:10] local.ERROR: Message {"exception":"..."}
         */
        preg_match_all(
            '/\[(.*?)\]\s+\w+\.ERROR:\s+(.*?)\s+(\{.*?)(?=\n\[\d{4}-\d{2}-\d{2}|\z)/s',
            $content,
            $matches,
            PREG_SET_ORDER
        );

        foreach ($matches as $match) {
            $dateTime = $match[1] ?? null;
            $message = trim($match[2] ?? '');
            $json = trim($match[3] ?? '{}');

            $context = json_decode($json, true);

            if (! is_array($context)) {
                $context = [];
            }

            $entries->push((object) [
                'file_name' => $fileName,
                'date_time' => $dateTime,

                'message' => $message,
                'exception' => $context['exception'] ?? 'N/A',
                'error_file' => $context['file'] ?? 'N/A',
                'line' => $context['line'] ?? 'N/A',

                'url' => $context['url'] ?? 'N/A',
                'method' => $context['method'] ?? 'N/A',
                'ip' => $context['ip'] ?? 'N/A',

                'user_id' => $context['user_id'] ?? null,
                'user_name' => $context['user_name'] ?? null,
                'user_email' => $context['user_email'] ?? null,

                'input' => $context['input'] ?? [],
                'trace' => $context['trace'] ?? '',
                'context' => $context,
            ]);
        }

        return $entries;
    }

    private function filterLogs(Collection $logs, Request $request): Collection
    {
        if ($request->filled('file_name')) {
            $logs = $logs->filter(function ($log) use ($request) {
                return $log->file_name === $request->file_name;
            });
        }

        if ($request->filled('exception')) {
            $logs = $logs->filter(function ($log) use ($request) {
                return str_contains(
                    strtolower($log->exception),
                    strtolower($request->exception)
                );
            });
        }

        if ($request->filled('message')) {
            $logs = $logs->filter(function ($log) use ($request) {
                return str_contains(
                    strtolower($log->message),
                    strtolower($request->message)
                );
            });
        }

        if ($request->filled('url')) {
            $logs = $logs->filter(function ($log) use ($request) {
                return str_contains(
                    strtolower($log->url),
                    strtolower($request->url)
                );
            });
        }

        if ($request->filled('ip')) {
            $logs = $logs->filter(function ($log) use ($request) {
                return str_contains(
                    strtolower($log->ip),
                    strtolower($request->ip)
                );
            });
        }

        if ($request->filled('start_date')) {
            $startDate = Carbon::parse($request->start_date)->startOfDay();

            $logs = $logs->filter(function ($log) use ($startDate) {
                if (! $log->date_time) {
                    return false;
                }

                return Carbon::parse($log->date_time)->greaterThanOrEqualTo($startDate);
            });
        }

        if ($request->filled('end_date')) {
            $endDate = Carbon::parse($request->end_date)->endOfDay();

            $logs = $logs->filter(function ($log) use ($endDate) {
                if (! $log->date_time) {
                    return false;
                }

                return Carbon::parse($log->date_time)->lessThanOrEqualTo($endDate);
            });
        }

        return $logs->values();
    }

    private function paginateLogs(Collection $logs, Request $request): LengthAwarePaginator
    {
        $perPage = 20;
        $currentPage = LengthAwarePaginator::resolveCurrentPage();

        $items = $logs
            ->slice(($currentPage - 1) * $perPage, $perPage)
            ->values();

        return new LengthAwarePaginator(
            $items,
            $logs->count(),
            $perPage,
            $currentPage,
            [
                'path' => $request->url(),
                'query' => $request->query(),
            ]
        );
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuditController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (!Auth::check() || !Auth::user()->isAdmin()) {
                abort(403, 'Unauthorized');
            }
            return $next($request);
        });
    }

    /**
     * Display a listing of audit logs (READ operation)
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $query = AuditLog::with(['booking', 'user']);

        // Filter by action
        if ($action = $request->query('action')) {
            $query->where('action', $action);
        }

        // Filter by user
        if ($userId = $request->query('user_id')) {
            $query->where('user_id', $userId);
        }

        // Filter by date range
        if ($startDate = $request->query('start_date')) {
            $query->whereDate('created_at', '>=', $startDate);
        }

        if ($endDate = $request->query('end_date')) {
            $query->whereDate('created_at', '<=', $endDate);
        }

        $logs = $query->orderByDesc('created_at')->paginate(30)->withQueryString();

        return view('admin.audit-logs.index', compact('logs'));
    }

    /**
     * Display the specified audit log (READ operation)
     *
     * @param AuditLog $auditLog
     * @return \Illuminate\View\View
     */
    public function show(AuditLog $auditLog)
    {
        $auditLog->load(['booking', 'user']);
        return view('admin.audit-logs.show', compact('auditLog'));
    }

    /**
     * Export audit logs to CSV (READ operation for export)
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function export(Request $request)
    {
        $query = AuditLog::with(['booking', 'user']);

        // Apply filters
        if ($action = $request->query('action')) {
            $query->where('action', $action);
        }

        if ($userId = $request->query('user_id')) {
            $query->where('user_id', $userId);
        }

        if ($startDate = $request->query('start_date')) {
            $query->whereDate('created_at', '>=', $startDate);
        }

        if ($endDate = $request->query('end_date')) {
            $query->whereDate('created_at', '<=', $endDate);
        }

        $logs = $query->orderByDesc('created_at')->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="audit-logs.csv"',
        ];

        $columns = [
            'ID', 'Booking ID', 'User', 'Action', 'Details', 'Created At'
        ];

        $callback = function() use ($logs, $columns) {
            $out = fopen('php://output', 'w');
            fputcsv($out, $columns);
            
            foreach ($logs as $log) {
                fputcsv($out, [
                    $log->id,
                    $log->booking_id,
                    $log->user->name ?? 'Unknown',
                    $log->action,
                    $log->details,
                    $log->created_at,
                ]);
            }
            
            fclose($out);
        };

        return response()->stream($callback, 200, $headers);
    }
}

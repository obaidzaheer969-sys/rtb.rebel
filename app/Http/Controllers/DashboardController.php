<?php

namespace App\Http\Controllers;

use App\Models\PingSubmission;
use App\Services\RingbaRtbService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use RuntimeException;
use Throwable;

class DashboardController extends Controller
{
    public function index(): View
    {
        $history = PingSubmission::query()
            ->latest()
            ->limit(25)
            ->get();

        return view('dashboard', [
            'history' => $history,
            'defaultTrafficSource' => config('services.ringba.default_traffic_source'),
        ]);
    }

    public function submit(Request $request, RingbaRtbService $ringba): JsonResponse
    {
        $validated = $request->validate([
            'phone' => ['required', 'string', 'regex:/^[0-9+]{10,15}$/'],
            'first_name' => ['nullable', 'string', 'max:100'],
            'last_name' => ['nullable', 'string', 'max:100'],
            'zip_code' => ['required', 'string', 'regex:/^[0-9]{5}(-[0-9]{4})?$/'],
            'traffic_source' => ['nullable', 'string', 'max:100'],
        ]);

        $validated['phone'] = preg_replace('/\D+/', '', $validated['phone']) ?? $validated['phone'];
        $validated['traffic_source'] = $validated['traffic_source']
            ?: config('services.ringba.default_traffic_source');

        try {
            $result = $ringba->bid($validated);
        } catch (RuntimeException $exception) {
            return response()->json([
                'ok' => false,
                'message' => $exception->getMessage(),
            ], 502);
        } catch (Throwable $exception) {
            report($exception);

            return response()->json([
                'ok' => false,
                'message' => 'Something went wrong while submitting. Please try again.',
            ], 500);
        }

        $submission = PingSubmission::query()->create([
            'phone' => $validated['phone'],
            'first_name' => $validated['first_name'] ?? null,
            'last_name' => $validated['last_name'] ?? null,
            'zip_code' => $validated['zip_code'],
            'traffic_source' => $validated['traffic_source'],
            'status' => $result['status'],
            'bid_amount' => $result['bid_amount'],
            'bid_id' => $result['bid_id'],
            'transfer_number' => $result['transfer_number'],
            'reject_reason' => $result['reject_reason'],
            'response_payload' => $result['raw'],
        ]);

        $history = PingSubmission::query()
            ->latest()
            ->limit(25)
            ->get()
            ->map(fn (PingSubmission $row) => $this->historyItem($row));

        return response()->json([
            'ok' => true,
            'result' => [
                'id' => $submission->id,
                'status' => $submission->status,
                'bid_id' => $submission->bid_id,
                'bid_amount' => $submission->bid_amount,
                'transfer_number' => $submission->transfer_number,
                'reject_reason' => $submission->reject_reason,
                'expire_in_seconds' => $result['expire_in_seconds'],
                'customer' => $submission->customer_name,
                'phone' => $submission->phone,
                'zip_code' => $submission->zip_code,
            ],
            'history' => $history,
        ]);
    }

    private function historyItem(PingSubmission $row): array
    {
        return [
            'id' => $row->id,
            'customer' => $row->customer_name,
            'phone' => $row->phone,
            'zip_code' => $row->zip_code,
            'status' => $row->status,
            'bid_amount' => $row->bid_amount,
            'transfer_number' => $row->transfer_number,
            'reject_reason' => $row->reject_reason,
            'created_at' => optional($row->created_at)->toDayDateTimeString(),
        ];
    }
}

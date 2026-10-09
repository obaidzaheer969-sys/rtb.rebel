<?php

namespace App\Services;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use RuntimeException;

class RingbaRtbService
{
    public function bid(array $payload): array
    {
        $endpoint = (string) config('services.ringba.endpoint');

        if ($endpoint === '') {
            throw new RuntimeException('Ringba RTB endpoint is not configured.');
        }

        $phone = preg_replace('/\D+/', '', (string) $payload['phone']) ?: (string) $payload['phone'];
        $zip = (string) $payload['zip_code'];
        $state = $payload['state'] ?? $this->stateFromZip($zip);

        // Official Ringba tags + aliases used by buyer filters.
        $query = array_filter([
            'CID' => $phone,
            'zipcode' => $zip,
            'ZipCode' => $zip,
            'State' => $state,
            'exposeCallerId' => 'yes',
            'firstName' => $payload['first_name'] ?? null,
            'lastName' => $payload['last_name'] ?? null,
            'trafficSource' => $payload['traffic_source'] ?? null,
        ], fn ($value) => $value !== null && $value !== '');

        try {
            // GET is the stable Ringba publisher method for this endpoint.
            $response = Http::timeout((int) config('services.ringba.timeout', 15))
                ->acceptJson()
                ->get($endpoint, $query);

            $data = $response->json() ?? [];

            // Fallback to JSON POST if GET body cannot be read.
            if (isset($data['error']['message']) && str_contains(strtolower($data['error']['message']), 'request body')) {
                $response = Http::timeout((int) config('services.ringba.timeout', 15))
                    ->acceptJson()
                    ->asJson()
                    ->post($endpoint, $query);
                $data = $response->json() ?? [];
            }
        } catch (ConnectionException $exception) {
            throw new RuntimeException('Unable to reach the bidding API. Please try again.', 0, $exception);
        }

        Log::info('Ringba RTB bid', [
            'endpoint' => $endpoint,
            'request' => $query,
            'http_status' => $response->status(),
            'response' => $data,
        ]);

        if (! $response->successful() && empty($data)) {
            throw new RuntimeException('Bidding API returned an unexpected response (HTTP '.$response->status().').');
        }

        $normalized = $this->normalize($data, $response->status());
        $normalized['request_payload'] = $query;
        $normalized['endpoint'] = $endpoint;

        return $normalized;
    }

    private function normalize(array $data, int $status): array
    {
        if (isset($data['error'])) {
            return [
                'status' => 'error',
                'bid_id' => $data['bidId'] ?? null,
                'bid_amount' => 0,
                'transfer_number' => null,
                'reject_reason' => $data['error']['message'] ?? 'Request error',
                'expire_in_seconds' => null,
                'raw' => $data,
                'http_status' => $status,
            ];
        }

        $bidAmount = (float) ($data['bidAmount'] ?? 0);
        $transferNumber = $data['phoneNumberNoPlus']
            ?? (isset($data['phoneNumber']) ? ltrim((string) $data['phoneNumber'], '+') : null);

        $rejected = $bidAmount <= 0 || ! empty($data['rejectReason']);

        return [
            'status' => $rejected ? 'rejected' : 'accepted',
            'bid_id' => $data['bidId'] ?? null,
            'bid_amount' => $bidAmount,
            'transfer_number' => $transferNumber,
            'reject_reason' => $data['rejectReason'] ?? null,
            'expire_in_seconds' => $data['expireInSeconds'] ?? null,
            'sip_address' => $data['sipAddress'] ?? null,
            'raw' => $data,
            'http_status' => $status,
        ];
    }

    private function stateFromZip(string $zip): ?string
    {
        $zipInt = (int) substr(preg_replace('/\D+/', '', $zip) ?? '', 0, 5);

        foreach ([
            [1001, 2791, 'MA'], [2801, 2940, 'RI'], [3031, 3897, 'NH'],
            [3901, 4992, 'ME'], [5001, 5907, 'VT'], [6001, 6928, 'CT'],
            [7001, 8989, 'NJ'], [10001, 14975, 'NY'], [15001, 19640, 'PA'],
            [19701, 19980, 'DE'], [20001, 20599, 'DC'], [20601, 21930, 'MD'],
            [22001, 24658, 'VA'], [24701, 26886, 'WV'], [27006, 28909, 'NC'],
            [29001, 29945, 'SC'], [30002, 31999, 'GA'], [32003, 34997, 'FL'],
            [35004, 36925, 'AL'], [37010, 38589, 'TN'], [38601, 39776, 'MS'],
            [40003, 42788, 'KY'], [43001, 45999, 'OH'], [46001, 47997, 'IN'],
            [48001, 49971, 'MI'], [50001, 52809, 'IA'], [53001, 54990, 'WI'],
            [55001, 56763, 'MN'], [57001, 57799, 'SD'], [58001, 58856, 'ND'],
            [59001, 59937, 'MT'], [60001, 62999, 'IL'], [63001, 65899, 'MO'],
            [66002, 67954, 'KS'], [68001, 69367, 'NE'], [70001, 71497, 'LA'],
            [71601, 72959, 'AR'], [73001, 74966, 'OK'], [75001, 79999, 'TX'],
            [80001, 81658, 'CO'], [82001, 83128, 'WY'], [83201, 83876, 'ID'],
            [84001, 84791, 'UT'], [85001, 86556, 'AZ'], [87001, 88439, 'NM'],
            [88901, 89883, 'NV'], [90001, 96162, 'CA'], [97001, 97920, 'OR'],
            [98001, 99403, 'WA'], [99501, 99950, 'AK'], [96701, 96898, 'HI'],
        ] as [$start, $end, $state]) {
            if ($zipInt >= $start && $zipInt <= $end) {
                return $state;
            }
        }

        return null;
    }
}

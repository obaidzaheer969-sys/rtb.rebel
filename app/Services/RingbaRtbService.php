<?php

namespace App\Services;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class RingbaRtbService
{
    public function bid(array $payload): array
    {
        $endpoint = (string) config('services.ringba.endpoint');

        if ($endpoint === '') {
            throw new RuntimeException('Ringba RTB endpoint is not configured.');
        }

        $body = array_filter([
            'CID' => $payload['phone'],
            'zipcode' => $payload['zip_code'],
            'exposeCallerId' => 'yes',
            'firstName' => $payload['first_name'] ?? null,
            'lastName' => $payload['last_name'] ?? null,
            'trafficSource' => $payload['traffic_source'] ?? null,
        ], fn ($value) => $value !== null && $value !== '');

        try {
            $response = Http::timeout((int) config('services.ringba.timeout', 15))
                ->acceptJson()
                ->asJson()
                ->post($endpoint, $body);
        } catch (ConnectionException $exception) {
            throw new RuntimeException('Unable to reach the bidding API. Please try again.', 0, $exception);
        }

        $data = $response->json() ?? [];

        if (! $response->successful() && empty($data)) {
            throw new RuntimeException('Bidding API returned an unexpected response (HTTP '.$response->status().').');
        }

        return $this->normalize($data, $response->status());
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
}

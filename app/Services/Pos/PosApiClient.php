<?php

namespace App\Services\Pos;

use App\Models\PosApiSetting;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;

class PosApiClient
{
    protected PosApiSetting $setting;

    public function __construct(?PosApiSetting $setting = null)
    {
        $this->setting = $setting ?: PosApiSetting::current();
    }

    public function settings(): array
    {
        return $this->get('settings');
    }

    public function categories(): array
    {
        return $this->get('categories');
    }

    public function brands(): array
    {
        return $this->get('brands');
    }

    public function products(int $limit = 50, int $page = 1, ?string $search = null): array
    {
        $query = compact('limit', 'page');

        if ($search !== null && trim($search) !== '') {
            $query['search'] = trim($search);
            $query['q'] = trim($search);
        }

        return $this->get('products', $query);
    }

    public function product($id): array
    {
        return $this->get('products/' . $id);
    }

    public function variations($variationIds = null): array
    {
        $headers = [];
        if (!empty($variationIds)) {
            $headers['VARIATIONS'] = is_array($variationIds) ? http_build_query($variationIds) : (string) $variationIds;
        }

        return $this->get('variations', [], $headers);
    }

    public function createCustomer(array $payload): array
    {
        return $this->post('customers', $payload);
    }

    public function createOrder(array $payload): array
    {
        return $this->post('orders', $payload);
    }

    protected function get(string $endpoint, array $query = [], array $headers = []): array
    {
        $this->assertReady();

        return $this->request()
            ->withHeaders($headers)
            ->get($this->url($endpoint), $query)
            ->throw()
            ->json() ?: [];
    }

    protected function post(string $endpoint, array $payload): array
    {
        $this->assertReady();

        return $this->request()
            ->post($this->url($endpoint), $payload)
            ->throw()
            ->json() ?: [];
    }

    protected function request()
    {
        return Http::timeout(25)
            ->acceptJson()
            ->withHeaders([
                'API-TOKEN' => $this->setting->api_token,
                'SHOP-DOMAIN' => $this->setting->shop_domain,
            ]);
    }

    protected function url(string $endpoint): string
    {
        return rtrim($this->setting->pos_base_url, '/') . '/api/ecom/' . ltrim($endpoint, '/');
    }

    protected function assertReady(): void
    {
        if (!$this->setting->exists || !$this->setting->is_active || empty($this->setting->api_token)) {
            throw new \RuntimeException('POS API settings are inactive or missing an API token.');
        }

        if (app()->bound('request') && $this->sameHostAndPort($this->setting->pos_base_url, request()->getSchemeAndHttpHost())) {
            throw new \RuntimeException('POS Base URL points to this Active eCommerce app. Set it to the Ultimate POS URL instead.');
        }
    }

    protected function sameHostAndPort(string $leftUrl, string $rightUrl): bool
    {
        $left = parse_url($leftUrl);
        $right = parse_url($rightUrl);

        $leftPort = $left['port'] ?? (($left['scheme'] ?? 'http') === 'https' ? 443 : 80);
        $rightPort = $right['port'] ?? (($right['scheme'] ?? 'http') === 'https' ? 443 : 80);

        return ($left['host'] ?? null) === ($right['host'] ?? null) && (int) $leftPort === (int) $rightPort;
    }

    public static function readableError(\Throwable $exception): string
    {
        if ($exception instanceof RequestException && $exception->response) {
            $body = $exception->response->json();
            if (is_array($body)) {
                return $body['message'] ?? $body['error'] ?? json_encode($body);
            }

            return $exception->response->body();
        }

        return $exception->getMessage();
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\PosApiSetting;
use App\Models\PosOrderExport;
use App\Services\Pos\PosApiClient;
use App\Services\Pos\PosSyncService;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\ValidationException;

class PosConnectorController extends Controller
{
    public function index()
    {
        $this->ensureConnectorTables();

        $setting = PosApiSetting::current();
        $recentExports = PosOrderExport::latest()->limit(10)->get();
        $productManager = null;

        if ($setting->exists && $setting->is_active && !empty($setting->api_token)) {
            try {
                $productManager = (new PosSyncService())->productManagerPage(
                    (int) request('limit', 50),
                    (int) request('page', 1)
                );
            } catch (\Throwable $e) {
                Log::warning('POS product manager failed to load', ['error' => $e->getMessage()]);
                flash(translate('POS product list could not load') . ': ' . PosApiClient::readableError($e))->warning();
            }
        }

        return view('backend.pos_connector.index', compact('setting', 'recentExports', 'productManager'));
    }

    public function update(Request $request)
    {
        $this->ensureConnectorTables();

        $data = $request->validate([
            'pos_base_url' => ['required', 'url'],
            'api_token' => ['nullable', 'string'],
            'shop_domain' => ['nullable', 'string', 'max:255'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $data['pos_base_url'] = rtrim($data['pos_base_url'], '/');
        $posPath = trim((string) parse_url($data['pos_base_url'], PHP_URL_PATH), '/');

        if (str_ends_with($posPath, 'ecommerce-api-settings')) {
            throw ValidationException::withMessages([
                'pos_base_url' => translate(
                    'Enter the Ultimate POS root URL only, without /ecommerce-api-settings.'
                ),
            ]);
        }

        if ($this->sameHostAndPort($data['pos_base_url'], $request->getSchemeAndHttpHost())) {
            flash(translate('POS Base URL must point to Ultimate POS, not this Active eCommerce URL.'))->error();
            return back()->withInput();
        }

        $setting = PosApiSetting::current();
        $setting->fill($data);
        $setting->is_active = $request->boolean('is_active');
        $setting->save();

        flash(translate('POS connection settings saved successfully'))->success();

        return back();
    }

    public function test()
    {
        $this->ensureConnectorTables();

        try {
            $response = (new PosSyncService())->testConnection();

            return response()->json([
                'success' => true,
                'message' => translate('POS connection successful'),
                'data' => $response,
            ]);
        } catch (\Throwable $e) {
            Log::error('POS connection test failed', ['error' => $e->getMessage()]);

            return response()->json([
                'success' => false,
                'message' => PosApiClient::readableError($e),
            ], 422);
        }
    }

    public function sync(Request $request, string $type)
    {
        $this->ensureConnectorTables();
        $this->allowLongRunningSync();

        try {
            $service = new PosSyncService();

            if ($type === 'categories') {
                $result = $service->syncCategories();
            } elseif ($type === 'brands') {
                $result = $service->syncBrands();
            } elseif ($type === 'products') {
                $result = $service->syncProducts();
            } elseif ($type === 'all') {
                $result = $service->syncAll();
            } else {
                abort(404);
            }

            flash(translate('POS sync completed successfully') . ': ' . json_encode($result))->success();
        } catch (\Throwable $e) {
            Log::error('POS sync failed', ['type' => $type, 'error' => $e->getMessage()]);
            flash(translate('POS sync failed') . ': ' . PosApiClient::readableError($e))->error();
        }

        return back();
    }

    protected function allowLongRunningSync(): void
    {
        $seconds = 600;

        if (function_exists('set_time_limit')) {
            @set_time_limit($seconds);
        }

        @ini_set('max_execution_time', (string) $seconds);
    }

    public function productsAction(Request $request)
    {
        $this->ensureConnectorTables();

        $data = $request->validate([
            'action' => ['required', 'in:sync_selected,remove_selected,remove_all'],
            'pos_ids' => ['nullable', 'array'],
            'pos_ids.*' => ['string'],
        ]);

        try {
            $service = new PosSyncService();

            if ($data['action'] === 'sync_selected') {
                $result = $service->syncSelectedProducts($data['pos_ids'] ?? []);
                flash(translate('Selected POS products synced') . ': ' . json_encode($result))->success();
            } elseif ($data['action'] === 'remove_selected') {
                $result = $service->removeImportedProducts($data['pos_ids'] ?? []);
                flash(translate('Selected imported POS products removed') . ': ' . json_encode($result))->success();
            } else {
                $result = $service->removeImportedProducts();
                flash(translate('All imported POS products removed') . ': ' . json_encode($result))->success();
            }
        } catch (\Throwable $e) {
            Log::error('POS product action failed', ['action' => $data['action'], 'error' => $e->getMessage()]);
            flash(translate('POS product action failed') . ': ' . PosApiClient::readableError($e))->error();
        }

        return back();
    }

    public function sendOrder($orderId)
    {
        $this->ensureConnectorTables();

        try {
            $order = Order::findOrFail($orderId);
            $response = (new PosSyncService())->sendOrder($order);

            if (!empty($response['success'])) {
                flash(translate('Order sent to POS successfully'))->success();
            } else {
                flash(translate('POS rejected the order') . ': ' . json_encode($response))->error();
            }
        } catch (\Throwable $e) {
            PosOrderExport::updateOrCreate(
                ['order_id' => $orderId],
                ['status' => 'failed', 'message' => PosApiClient::readableError($e)]
            );
            Log::error('POS order export failed', ['order_id' => $orderId, 'error' => $e->getMessage()]);
            flash(translate('Order send to POS failed') . ': ' . PosApiClient::readableError($e))->error();
        }

        return back();
    }

    public function sendPendingOrders()
    {
        $this->ensureConnectorTables();

        $sent = 0;
        $failed = 0;

        Order::whereDoesntHave('orderDetails', function ($query) {
            $query->whereNull('product_id');
        })
            ->whereNotIn('id', PosOrderExport::where('status', 'sent')->pluck('order_id'))
            ->latest()
            ->limit(50)
            ->get()
            ->each(function ($order) use (&$sent, &$failed) {
                try {
                    $response = (new PosSyncService())->sendOrder($order);
                    !empty($response['success']) ? $sent++ : $failed++;
                } catch (\Throwable $e) {
                    $failed++;
                    Log::error('Pending POS order export failed', ['order_id' => $order->id, 'error' => $e->getMessage()]);
                }
            });

        flash(translate('Pending POS order export finished') . ". Sent: {$sent}, Failed: {$failed}")->success();

        return back();
    }

    protected function ensureConnectorTables(): void
    {
        if (!Schema::hasTable('pos_api_settings')) {
            Schema::create('pos_api_settings', function (Blueprint $table) {
                $table->id();
                $table->string('pos_base_url')->default('http://localhost');
                $table->text('api_token')->nullable();
                $table->string('shop_domain')->nullable()->default('127.0.0.1:8001');
                $table->boolean('is_active')->default(true);
                $table->timestamp('last_sync_at')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('pos_sync_mappings')) {
            Schema::create('pos_sync_mappings', function (Blueprint $table) {
                $table->id();
                $table->string('entity_type', 30);
                $table->string('pos_id', 100);
                $table->unsignedBigInteger('ecommerce_id');
                $table->timestamps();
                $table->unique(['entity_type', 'pos_id']);
                $table->index(['entity_type', 'ecommerce_id']);
            });
        }

        if (!Schema::hasTable('pos_order_exports')) {
            Schema::create('pos_order_exports', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('order_id')->unique();
                $table->string('pos_transaction_id')->nullable();
                $table->string('pos_customer_id')->nullable();
                $table->string('status')->default('pending');
                $table->text('message')->nullable();
                $table->timestamp('sent_at')->nullable();
                $table->timestamps();
            });
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
}

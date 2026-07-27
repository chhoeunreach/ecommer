<?php

namespace App\Services;

use App\Jobs\DownloadPhoneImage;
use App\Models\PhoneImage;
use App\Models\PhoneLibraryUpdateLog;
use App\Models\PhoneModel;
use App\Repositories\PhoneLibraryRepository;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

/**
 * Application service for phone library actions.
 */
class PhoneLibraryService
{
    public function __construct(private PhoneLibraryRepository $repository)
    {
    }

    /**
     * Store or update a complete phone model payload.
     */
    public function saveModel(array $payload): PhoneModel
    {
        return DB::transaction(fn () => $this->repository->upsertModel($payload));
    }

    /**
     * Queue a remote image download while avoiding duplicate URLs.
     */
    public function queueImageDownload(PhoneModel $model, string $url, string $type = 'main', ?int $variantId = null): void
    {
        $exists = PhoneImage::where('source_url', $url)->exists();

        if (!$exists) {
            DownloadPhoneImage::dispatch($model->id, $url, $type, $variantId);
        }
    }

    /**
     * Update the library from an array source payload.
     */
    public function updateFromPayload(array $payload, ?int $userId = null, ?string $source = null): PhoneLibraryUpdateLog
    {
        $log = PhoneLibraryUpdateLog::create([
            'user_id' => $userId,
            'source' => $source,
            'status' => 'running',
        ]);

        try {
            $counts = ['brands_count' => 0, 'models_count' => 0, 'variants_count' => 0, 'images_count' => 0];

            DB::transaction(function () use ($payload, &$counts): void {
                foreach (Arr::get($payload, 'models', []) as $modelPayload) {
                    $model = $this->repository->upsertModel($modelPayload);
                    $counts['models_count']++;
                    $counts['brands_count']++;
                    $counts['variants_count'] += $model->variants()->count();

                    foreach (Arr::get($modelPayload, 'images', []) as $image) {
                        $this->queueImageDownload($model, $image['url'], Arr::get($image, 'type', 'main'));
                        $counts['images_count']++;
                    }
                }
            });

            $log->update($counts + ['status' => 'completed', 'message' => 'Phone library update completed.']);
        } catch (\Throwable $exception) {
            $log->update(['status' => 'failed', 'message' => $exception->getMessage()]);
            throw $exception;
        }

        return $log->fresh();
    }
}

<?php

namespace App\Jobs;

use App\Models\PhoneImage;
use App\Models\PhoneModel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Download and deduplicate remote phone images into local storage.
 */
class DownloadPhoneImage implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        private int $phoneModelId,
        private string $url,
        private string $type = 'main',
        private ?int $phoneVariantId = null
    ) {
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $model = PhoneModel::findOrFail($this->phoneModelId);
        $response = Http::timeout(20)->get($this->url);

        if (!$response->successful()) {
            $this->fail('Phone image download failed: ' . $this->url);
            return;
        }

        $body = $response->body();
        $hash = hash('sha256', $body);

        if (PhoneImage::where('hash', $hash)->exists()) {
            return;
        }

        $extension = pathinfo(parse_url($this->url, PHP_URL_PATH) ?: '', PATHINFO_EXTENSION) ?: 'jpg';
        $path = trim(config('phone_library.image_path'), '/') . '/'
            . $model->slug . '/'
            . Str::slug($this->type) . '-' . $hash . '.' . strtolower($extension);

        Storage::disk(config('phone_library.image_disk'))->put($path, $body);

        PhoneImage::create([
            'phone_model_id' => $model->id,
            'phone_variant_id' => $this->phoneVariantId,
            'type' => $this->type,
            'path' => $path,
            'source_url' => $this->url,
            'hash' => $hash,
            'is_primary' => !$model->images()->exists(),
        ]);
    }
}

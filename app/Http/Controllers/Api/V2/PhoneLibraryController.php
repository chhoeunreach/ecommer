<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Controller;
use App\Http\Resources\V2\PhoneLibraryResource;
use App\Http\Resources\V2\PhoneVariantResource;
use App\Models\PhoneBrand;
use App\Models\PhoneModel;
use App\Models\PhoneVariant;
use App\Repositories\PhoneLibraryRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\JsonResponse;

/**
 * REST API for browsing the independent phone library.
 */
class PhoneLibraryController extends Controller
{
    public function __construct(private PhoneLibraryRepository $repository)
    {
    }

    /**
     * List searchable phone models.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $models = $this->repository->searchModels($request->all(), (int) $request->input('per_page', 20));

        return PhoneLibraryResource::collection($models);
    }

    /**
     * Show one phone model.
     */
    public function show(int $id): PhoneLibraryResource
    {
        return new PhoneLibraryResource($this->repository->findModel($id));
    }

    /**
     * List phone brands.
     */
    public function brands(): JsonResponse
    {
        return response()->json([
            'data' => PhoneBrand::orderBy('name')->get(['id', 'name', 'slug', 'country', 'website', 'is_active']),
        ]);
    }

    /**
     * List phone models.
     */
    public function models(Request $request): AnonymousResourceCollection
    {
        $query = PhoneModel::with(['brand', 'specification', 'primaryImage'])->latest();

        if ($request->filled('brand_id')) {
            $query->where('phone_brand_id', $request->input('brand_id'));
        }

        return PhoneLibraryResource::collection($query->paginate((int) $request->input('per_page', 20)));
    }

    /**
     * List phone variant templates.
     */
    public function variants(Request $request): AnonymousResourceCollection
    {
        $query = PhoneVariant::with('model.brand')->latest();

        if ($request->filled('model_id')) {
            $query->where('phone_model_id', $request->input('model_id'));
        }

        return PhoneVariantResource::collection($query->paginate((int) $request->input('per_page', 20)));
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Requests\PhoneBrandRequest;
use App\Http\Requests\PhoneImageRequest;
use App\Http\Requests\PhoneModelRequest;
use App\Http\Requests\PhoneSpecificationRequest;
use App\Http\Requests\PhoneVariantRequest;
use App\Models\PhoneBrand;
use App\Models\PhoneImage;
use App\Models\PhoneModel;
use App\Models\PhoneSpecification;
use App\Models\PhoneVariant;
use App\Repositories\PhoneLibraryRepository;
use App\Services\PhoneLibraryService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

/**
 * Admin CRUD controller for the independent mobile phone library.
 */
class PhoneLibraryController extends Controller
{
    public function __construct(
        private PhoneLibraryRepository $repository,
        private PhoneLibraryService $service
    ) {
        $this->middleware(['permission:phone_library.view'])->only(['index', 'show']);
        $this->middleware(['permission:phone_library.create'])->only(['create', 'storeBrand', 'storeModel', 'storeVariant', 'storeSpecification']);
        $this->middleware(['permission:phone_library.import'])->only(['storeImage']);
        $this->middleware(['permission:phone_library.edit'])->only(['edit', 'updateBrand', 'updateModel', 'updateVariant', 'updateSpecification', 'updateImage']);
        $this->middleware(['permission:phone_library.delete'])->only(['destroyBrand', 'destroyModel', 'destroyVariant', 'destroyImage']);
    }

    /**
     * Display searchable phone models.
     */
    public function index(Request $request): View
    {
        $filters = $request->only(['brand', 'model', 'storage', 'color', 'year', 'has_5g', 'chipset', 'display_size', 'status']);
        $models = $this->repository->searchModels($filters, 20);
        $brands = PhoneBrand::orderBy('name')->get();

        return view('backend.phone_library.index', compact('models', 'brands', 'filters'));
    }

    /**
     * Display a phone model preview.
     */
    public function show(int $id): View
    {
        $model = $this->repository->findModel($id);

        return view('backend.phone_library.show', compact('model'));
    }

    /**
     * Show a combined create form for brand and model data.
     */
    public function create(): View
    {
        return view('backend.phone_library.create', [
            'brands' => PhoneBrand::orderBy('name')->get(),
        ]);
    }

    /**
     * Store a phone brand.
     */
    public function storeBrand(PhoneBrandRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['slug'] = $data['slug'] ?? Str::slug($data['name']);
        $data['is_active'] = $request->boolean('is_active', true);

        PhoneBrand::create($data);

        flash(translate('Phone brand saved successfully'))->success();
        return back();
    }

    /**
     * Store a phone model.
     */
    public function storeModel(PhoneModelRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $brand = PhoneBrand::findOrFail($data['phone_brand_id']);
        $data['slug'] = $data['slug'] ?? Str::slug($brand->name . '-' . $data['model_name']);

        PhoneModel::create($data);

        flash(translate('Phone model saved successfully'))->success();
        return redirect()->route('phone-library.index');
    }

    /**
     * Store or update model specifications.
     */
    public function storeSpecification(PhoneSpecificationRequest $request): RedirectResponse
    {
        PhoneSpecification::updateOrCreate(
            ['phone_model_id' => $request->validated('phone_model_id')],
            $request->validated()
        );

        flash(translate('Phone specifications saved successfully'))->success();
        return back();
    }

    /**
     * Store a phone variant template.
     */
    public function storeVariant(PhoneVariantRequest $request): RedirectResponse
    {
        PhoneVariant::create($request->validated() + [
            'currency' => $request->input('currency', 'USD'),
            'is_active' => $request->boolean('is_active', true),
        ]);

        flash(translate('Phone variant saved successfully'))->success();
        return back();
    }

    /**
     * Store a local image record or queue a source download.
     */
    public function storeImage(PhoneImageRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $model = PhoneModel::findOrFail($data['phone_model_id']);

        if (!empty($data['source_url']) && empty($data['path'])) {
            $this->service->queueImageDownload($model, $data['source_url'], $data['type'], $data['phone_variant_id'] ?? null);
        } else {
            PhoneImage::create($data + ['is_primary' => $request->boolean('is_primary')]);
        }

        flash(translate('Phone image saved successfully'))->success();
        return back();
    }

    /**
     * Update a phone brand.
     */
    public function updateBrand(PhoneBrandRequest $request, PhoneBrand $brand): RedirectResponse
    {
        $data = $request->validated();
        $data['slug'] = $data['slug'] ?? Str::slug($data['name']);
        $data['is_active'] = $request->boolean('is_active', true);
        $brand->update($data);

        flash(translate('Phone brand updated successfully'))->success();
        return back();
    }

    /**
     * Update a phone model.
     */
    public function updateModel(PhoneModelRequest $request, PhoneModel $model): RedirectResponse
    {
        $data = $request->validated();
        $brand = PhoneBrand::findOrFail($data['phone_brand_id']);
        $data['slug'] = $data['slug'] ?? Str::slug($brand->name . '-' . $data['model_name']);
        $model->update($data);

        flash(translate('Phone model updated successfully'))->success();
        return back();
    }

    /**
     * Update phone specifications.
     */
    public function updateSpecification(PhoneSpecificationRequest $request, PhoneSpecification $specification): RedirectResponse
    {
        $specification->update($request->validated());

        flash(translate('Phone specifications updated successfully'))->success();
        return back();
    }

    /**
     * Update a phone variant.
     */
    public function updateVariant(PhoneVariantRequest $request, PhoneVariant $variant): RedirectResponse
    {
        $variant->update($request->validated() + [
            'currency' => $request->input('currency', $variant->currency),
            'is_active' => $request->boolean('is_active', $variant->is_active),
        ]);

        flash(translate('Phone variant updated successfully'))->success();
        return back();
    }

    /**
     * Update a phone image.
     */
    public function updateImage(PhoneImageRequest $request, PhoneImage $image): RedirectResponse
    {
        $image->update($request->validated() + ['is_primary' => $request->boolean('is_primary')]);

        flash(translate('Phone image updated successfully'))->success();
        return back();
    }

    /**
     * Delete a phone brand.
     */
    public function destroyBrand(PhoneBrand $brand): RedirectResponse
    {
        $brand->delete();

        flash(translate('Phone brand deleted successfully'))->success();
        return back();
    }

    /**
     * Delete a phone model.
     */
    public function destroyModel(PhoneModel $model): RedirectResponse
    {
        $model->delete();

        flash(translate('Phone model deleted successfully'))->success();
        return redirect()->route('phone-library.index');
    }

    /**
     * Delete a phone variant.
     */
    public function destroyVariant(PhoneVariant $variant): RedirectResponse
    {
        $variant->delete();

        flash(translate('Phone variant deleted successfully'))->success();
        return back();
    }

    /**
     * Delete a phone image.
     */
    public function destroyImage(PhoneImage $image): RedirectResponse
    {
        $image->delete();

        flash(translate('Phone image deleted successfully'))->success();
        return back();
    }
}

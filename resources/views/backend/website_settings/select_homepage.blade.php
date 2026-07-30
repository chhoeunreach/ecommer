@extends('backend.layouts.app')

@section('content')

<div class="aiz-titlebar text-left mt-2 mb-3 pb-2 border-bottom border-gray">
	<div class="row align-items-center">
		<div class="col">
			<h1 class="h3">{{ translate('Select Homepage') }}</h1>
		</div>
		{{-- <div class="col text-right">
			<a class="btn has-transition btn-xs btn-light hov-svg-danger rounded-0" href="{{ route('home') }}"
				target="_blank" data-toggle="tooltip" data-placement="top" data-title="{{ translate('View Tutorial Video') }}">
				<svg xmlns="http://www.w3.org/2000/svg" width="19.887" height="16" viewBox="0 0 19.887 16">
					<path id="_42fbab5a39cb8436403668a76e5a774b" data-name="42fbab5a39cb8436403668a76e5a774b" d="M18.723,8H5.5A3.333,3.333,0,0,0,2.17,11.333v9.333A3.333,3.333,0,0,0,5.5,24h13.22a3.333,3.333,0,0,0,3.333-3.333V11.333A3.333,3.333,0,0,0,18.723,8Zm-3.04,8.88-5.47,2.933a1,1,0,0,1-1.473-.88V13.067a1,1,0,0,1,1.473-.88l5.47,2.933a1,1,0,0,1,0,1.76Zm-5.61-3.257L14.5,16l-4.43,2.377Z" transform="translate(-2.17 -8)" fill="#9da3ae"/>
				</svg>
			</a>
		</div> --}}
	</div>
</div>
<div class="">
	<div class="card rounded-0">
		<div class="card-body p-2rem">
			<form action="{{ route('business_settings.update') }}" method="POST" enctype="multipart/form-data">
				@csrf
				<input type="hidden" name="types[]" value="homepage_select">
				<div class="row">
					<!-- Home Classic -->
					<div class="col-xxl-3 col-lg-4 col-sm-6 my-3">
						<label class="aiz-megabox d-block mb-3">
							<input value="classic" type="radio" name="homepage_select" @if((get_setting('homepage_select') == null) || (get_setting('homepage_select') == 'classic')) checked @endif>
							<span class="d-block aiz-megabox-elem rounded-0 img-overlay">
								<div class="h-350px w-100 overflow-hidden">
									<img src="{{ static_asset('assets/img/pages/home-classic.webp') }}" class="w-100" alt="home-page-1">
								</div>
							</span>
						</label>
						<div class="d-flex flex-wrap justify-content-between align-items-center">
							<span class="fs-14 fw-500 text-dark">{{ translate('Homepage 1 - Classic') }}</span>
							<span>
								<button type="button" class="btn btn-xs btn-danger rounded-0 homepage-preview-btn"
									data-preview-src="{{ static_asset('assets/img/pages/home-classic.webp') }}">{{ translate('View') }}</button>
							</span>
						</div>
					</div>
					<!-- Home Metro -->
					<div class="col-xxl-3 col-lg-4 col-sm-6 my-3">
						<label class="aiz-megabox d-block mb-3">
							<input value="metro" type="radio" name="homepage_select" @if(get_setting('homepage_select') == 'metro') checked @endif>
							<span class="d-block aiz-megabox-elem rounded-0 img-overlay">
								<div class="h-350px w-100 overflow-hidden">
									<img src="{{ static_asset('assets/img/pages/home-metro.webp') }}" class="w-100" alt="home-page-2">
								</div>
							</span>
						</label>
						<div class="d-flex flex-wrap justify-content-between align-items-center">
							<span class="fs-14 fw-500 text-dark">{{ translate('Homepage 2 - Metro') }}</span>
							<span>
								<button type="button" class="btn btn-xs btn-danger rounded-0 homepage-preview-btn"
									data-preview-src="{{ static_asset('assets/img/pages/home-metro.webp') }}">{{ translate('View') }}</button>
							</span>
						</div>
					</div>
					<!-- Home Minima -->
					<div class="col-xxl-3 col-lg-4 col-sm-6 my-3">
						<label class="aiz-megabox d-block mb-3">
							<input value="minima" type="radio" name="homepage_select" @if(get_setting('homepage_select') == 'minima') checked @endif>
							<span class="d-block aiz-megabox-elem rounded-0 img-overlay">
								<div class="h-350px w-100 overflow-hidden">
									<img src="{{ static_asset('assets/img/pages/home-minima.webp') }}" class="w-100" alt="home-page-3">
								</div>
							</span>
						</label>
						<div class="d-flex flex-wrap justify-content-between align-items-center">
							<span class="fs-14 fw-500 text-dark">{{ translate('Homepage 3 - Minima') }}</span>
							<span>
								<button type="button" class="btn btn-xs btn-danger rounded-0 homepage-preview-btn"
									data-preview-src="{{ static_asset('assets/img/pages/home-minima.webp') }}">{{ translate('View') }}</button>
							</span>
						</div>
					</div>
					<!-- Home Megamart -->
					<div class="col-xxl-3 col-lg-4 col-sm-6 my-3">
						<label class="aiz-megabox d-block mb-3">
							<input value="megamart" type="radio" name="homepage_select" @if(get_setting('homepage_select') == 'megamart') checked @endif>
							<span class="d-block aiz-megabox-elem rounded-0 img-overlay">
								<div class="h-350px w-100 overflow-hidden">
									<img src="{{ static_asset('assets/img/pages/home-megamart.webp') }}" class="w-100" alt="home-page-4">
								</div>
							</span>
						</label>
						<div class="d-flex flex-wrap justify-content-between align-items-center">
							<span class="fs-14 fw-500 text-dark">{{ translate('Homepage 4 - Megamart') }}</span>
							<span>
								<button type="button" class="btn btn-xs btn-danger rounded-0 homepage-preview-btn"
									data-preview-src="{{ static_asset('assets/img/pages/home-megamart.webp') }}">{{ translate('View') }}</button>
							</span>
						</div>
					</div>
					<!-- Home Re-Classic -->
					<div class="col-xxl-3 col-lg-4 col-sm-6 my-3">
						<label class="aiz-megabox d-block mb-3">
							<input value="reclassic" type="radio" name="homepage_select" @if(get_setting('homepage_select') == 'reclassic') checked @endif>
							<span class="d-block aiz-megabox-elem rounded-0 img-overlay">
								<div class="h-350px w-100 overflow-hidden">
									<img src="{{ static_asset('assets/img/pages/home-reclassic.webp') }}" class="w-100" alt="home-page-5">
								</div>
							</span>
						</label>
						<div class="d-flex flex-wrap justify-content-between align-items-center">
							<span class="fs-14 fw-500 text-dark">{{ translate('Homepage 5 - Re-Classic') }}</span>
							<span>
								<button type="button" class="btn btn-xs btn-danger rounded-0 homepage-preview-btn"
									data-preview-src="{{ static_asset('assets/img/pages/home-reclassic.webp') }}">{{ translate('View') }}</button>
							</span>
						</div>
					</div>

					<!-- Home Edge -->
					<div class="col-xxl-3 col-lg-4 col-sm-6 my-3">
						<label class="aiz-megabox d-block mb-3">
							<input value="thecore" type="radio" name="homepage_select" @if(get_setting('homepage_select') == 'thecore') checked @endif>
							<span class="d-block aiz-megabox-elem rounded-0 img-overlay">
								<div class="h-350px w-100 overflow-hidden">
									<img src="{{ static_asset('assets/img/pages/home-thecore.png') }}" class="w-100" alt="home-page-6">
								</div>
							</span>
						</label>
						<div class="d-flex flex-wrap justify-content-between align-items-center">
							<span class="fs-14 fw-500 text-dark">{{ translate('Homepage 6 - The-Core') }}</span>
							<span>
								<button type="button" class="btn btn-xs btn-danger rounded-0 homepage-preview-btn"
									data-preview-src="{{ static_asset('assets/img/pages/home-thecore.png') }}">{{ translate('View') }}</button>
							</span>
						</div>
					</div>

					<!-- Home Nexa -->
					<div class="col-xxl-3 col-lg-4 col-sm-6 my-3">
						<label class="aiz-megabox d-block mb-3">
							<input value="nexa" type="radio" name="homepage_select" @if(get_setting('homepage_select') == 'nexa') checked @endif>
							<span class="d-block aiz-megabox-elem rounded-0 img-overlay">
								<div class="h-350px w-100 overflow-hidden">
									<img src="{{ static_asset('assets/img/pages/home-nexa.webp') }}" class="w-100" alt="home-page-7">
								</div>
							</span>
						</label>
						<div class="d-flex flex-wrap justify-content-between align-items-center">
							<span class="fs-14 fw-500 text-dark">{{ translate('Homepage 7 - Nexa') }}</span>
							<span>
								<button type="button" class="btn btn-xs btn-danger rounded-0 homepage-preview-btn"
									data-preview-src="{{ static_asset('assets/img/pages/home-nexa.webp') }}">{{ translate('View') }}</button>
							</span>
						</div>
					</div>

					<!-- Home KY-Stor -->
					<div class="col-xxl-3 col-lg-4 col-sm-6 my-3">
						<label class="aiz-megabox d-block mb-3">
							<input value="kystor" type="radio" name="homepage_select" @if(get_setting('homepage_select') == 'kystor') checked @endif>
							<span class="d-block aiz-megabox-elem rounded-0 img-overlay">
								<div class="h-350px w-100 overflow-hidden">
									<img src="{{ static_asset('assets/img/pages/home-kystor.png') }}" class="w-100" alt="home-page-8">
								</div>
							</span>
						</label>
						<div class="d-flex flex-wrap justify-content-between align-items-center">
							<span class="fs-14 fw-500 text-dark">{{ translate('Homepage 8 - KY-Stor') }}</span>
							<span>
								<button type="button" class="btn btn-xs btn-danger rounded-0 homepage-preview-btn"
									data-preview-src="{{ static_asset('assets/img/pages/home-kystor.png') }}">{{ translate('View') }}</button>
							</span>
						</div>
					</div>
				</div>
				<div class="row bg-light p-3 mt-5">
					<div class="col-md-8 d-none d-md-block">
						<div class="d-flex align-items-center">
							<div class="text-secondary mr-3"><i class="las la-4x la-sliders-h"></i></div>
							<div>
								<h4 class="fs-16 text-secondary">{{ translate('Configure your page layout') }}</h4>
								<small class="fs-12 text-secondary">{{ translate('Each page contain different layout, choose one to bundle it in your Layout.') }}</small>
							</div>
						</div>
					</div>
					<div class="col-md-4 d-flex align-items-center justify-content-end">
						<!-- Save Button -->
						<button type="submit" class="btn btn-success w-230px btn-md rounded-2 fs-14 fw-700 shadow-success">{{ translate('Save') }}</button>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>
@endsection

@section('modal')
	<div class="image-show-overlay" id="image-show-overlay" role="dialog" aria-modal="true" aria-hidden="true">
		<div class="d-flex justify-content-end my-3 mr-3">
			<button type="button" class="btn text-white d-flex align-items-center justify-content-center homepage-preview-close"
				aria-label="{{ translate('Close') }}"><i class="las la-2x la-times"></i></button>
		</div>
		<div class="overlay-img">
			<img src="{{ static_asset('assets/img/pages/home-metro.webp') }}" class="w-100" alt="img-show">
		</div>
	</div>
@endsection

@section('script')
	<style>
		#image-show-overlay {
			position: fixed;
			inset: 0;
			width: 100vw;
			height: 100vh;
		}

		body.homepage-preview-open {
			overflow: hidden;
		}
	</style>
	<script>
		function imageShowOverlay(img) {
			var overlay = document.getElementById('image-show-overlay');
			var previewImage = overlay ? overlay.querySelector('.overlay-img img') : null;

			if (!overlay || !previewImage || !img) {
				return;
			}

			previewImage.src = img;
			overlay.classList.add('show');
			overlay.setAttribute('aria-hidden', 'false');
			document.body.classList.add('homepage-preview-open');
		}

		function imageHideOverlay() {
			var overlay = document.getElementById('image-show-overlay');

			if (overlay) {
				overlay.classList.remove('show');
				overlay.setAttribute('aria-hidden', 'true');
			}

			document.body.classList.remove('homepage-preview-open');
		}

		document.addEventListener('click', function (event) {
			var previewButton = event.target.closest('.homepage-preview-btn');

			if (previewButton) {
				event.preventDefault();
				imageShowOverlay(previewButton.getAttribute('data-preview-src'));
				return;
			}

			if (event.target.closest('.homepage-preview-close')) {
				imageHideOverlay();
				return;
			}

			var overlay = document.getElementById('image-show-overlay');
			if (overlay && event.target === overlay) {
				imageHideOverlay();
			}
		});

		document.addEventListener('keydown', function (event) {
			if (event.key === 'Escape') {
				imageHideOverlay();
			}
		});
	</script>
@endsection

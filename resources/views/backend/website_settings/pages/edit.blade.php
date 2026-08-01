@extends('backend.layouts.app')

@section('content')
<div class="aiz-titlebar text-left mt-2 mb-3">
	<div class="row align-items-center">
		<div class="col">
			<h1 class="h3">{{ translate('Edit Page Information') }}</h1>
		</div>
	</div>
</div>
<div class="card">
	<ul class="nav nav-tabs nav-fill language-bar">
		@foreach (get_all_active_language() as $key => $language)
			<li class="nav-item">
				<a class="nav-link text-reset @if ($language->code == $lang) active @endif py-3" href="{{ route('custom-pages.edit', ['id'=>$page->slug, 'lang'=> $language->code] ) }}">
					<img src="{{ static_asset('assets/img/flags/'.$language->code.'.png') }}" height="11" class="mr-1">
					<span>{{$language->name}}</span>
				</a>
			</li>
		@endforeach
	</ul>

	<form class="p-4" action="{{ route('custom-pages.update', $page->id) }}" method="POST" enctype="multipart/form-data">
		@csrf
		<input type="hidden" name="_method" value="PATCH">
		<input type="hidden" name="lang" value="{{ $lang }}">
		@php
			$termsCards = $page->type == 'terms_conditions_page'
				? json_decode(get_setting('terms_page_cards', '[]', $lang), true)
				: [];
			$termsCards = is_array($termsCards) ? $termsCards : [];
			$baseColor = get_setting('base_color', '#1b74e4');
			$baseColor = preg_match('/^#[0-9a-fA-F]{6}$/', (string) $baseColor) ? $baseColor : '#1b74e4';
			$termsColorDefaults = [
				'hero_start' => $baseColor,
				'hero_end' => '#111723',
				'hero_text' => '#ffffff',
				'accent' => '#ffc519',
				'card_background' => '#ffffff',
				'heading' => '#111723',
				'text' => '#4e5561',
			];
			$storedTermsColors = $page->type == 'terms_conditions_page'
				? json_decode(get_setting('terms_page_colors', '{}'), true)
				: [];
			$storedTermsColors = is_array($storedTermsColors) ? $storedTermsColors : [];
			$termsColors = [];
			foreach ($termsColorDefaults as $colorKey => $defaultColor) {
				$colorValue = $storedTermsColors[$colorKey] ?? $defaultColor;
				$termsColors[$colorKey] = preg_match('/^#[0-9a-fA-F]{6}$/', (string) $colorValue)
					? $colorValue
					: $defaultColor;
			}
		@endphp

		<div class="card-header px-0">
			<h6 class="fw-600 mb-0">{{ translate('Page Content') }}</h6>
		</div>
		<div class="card-body px-0">
			<div class="form-group row">
				<label class="col-sm-2 col-from-label" for="name">{{translate('Title')}} <span class="text-danger">*</span> <i class="las la-language text-danger" title="{{translate('Translatable')}}"></i></label>
				<div class="col-sm-10">
					<input type="text" class="form-control" placeholder="{{translate('Title')}}" name="title" value="{{ $page->getTranslation('title',$lang) }}" required>
				</div>
			</div>


				<div class="form-group row">
					<label class="col-sm-2 col-from-label" for="name">{{translate('Link')}} <span class="text-danger">*</span></label>
					<div class="col-sm-10">
						<div class="input-group d-block d-md-flex">
							@if($page->type == 'custom_page')
								<div class="input-group-prepend"><span class="input-group-text flex-grow-1">{{ route('home') }}/</span></div>
								<input type="text" class="form-control w-100 w-md-auto" placeholder="{{ translate('Slug') }}" name="slug" value="{{ $page->slug }}">
							@else
								<input class="form-control w-100 w-md-auto" value="{{ route('home') }}/{{ $page->slug }}" disabled>
							@endif
						</div>
						<small class="form-text text-muted">{{ translate('Use character, number, hypen only') }}</small>
					</div>
				</div>

			<div class="form-group row">
				<label class="col-sm-2 col-from-label" for="name">{{translate('Add Content')}} <span class="text-danger">*</span></label>
				<div class="col-sm-10">
					<textarea
						class="aiz-text-editor form-control"
						placeholder="{{translate('Content..')}}"
						data-buttons='[["font", ["bold", "underline", "italic", "clear"]],["para", ["ul", "ol", "paragraph"]],["style", ["style"]],["color", ["color"]],["table", ["table"]],["insert", ["link", "picture", "video"]],["view", ["fullscreen", "codeview", "undo", "redo"]],["custom", ["clearText"]]]'
						data-min-height="300"
						name="content"
						@if($page->type != 'terms_conditions_page') required @endif
					>{!! $page->getTranslation('content',$lang) !!}</textarea>
				</div>
			</div>

			@if($page->type == 'terms_conditions_page')
				<div class="border-top pt-4 mt-4">
					<div class="d-flex justify-content-between align-items-center mb-3">
						<div>
							<h6 class="fw-600 mb-1">{{ translate('Terms Link Cards') }}</h6>
							<p class="text-muted fs-12 mb-0">{{ translate('Add up to 12 cards with an image, title, description and link.') }}</p>
						</div>
					</div>

					<div class="terms-cards-target">
						@foreach($termsCards as $index => $card)
							<div class="terms-card-row remove-parent border border-dashed rounded p-3 mb-3 position-relative">
								<div class="row gutters-10">
									<div class="col-md-4">
										<label class="fs-12 fw-600">{{ translate('Image') }}</label>
										<div class="input-group" data-toggle="aizuploader" data-type="image" data-multiple="false">
											<div class="input-group-prepend">
												<div class="input-group-text bg-soft-secondary">{{ translate('Browse') }}</div>
											</div>
											<div class="form-control file-amount">{{ translate('Choose File') }}</div>
											<input type="hidden" name="terms_cards[{{ $index }}][image]" class="selected-files" value="{{ $card['image'] ?? '' }}">
										</div>
										<div class="file-preview box sm"></div>
									</div>
									<div class="col-md-8">
										<div class="form-group">
											<label class="fs-12 fw-600">{{ translate('Title') }}</label>
											<input type="text" class="form-control" maxlength="100" name="terms_cards[{{ $index }}][title]" value="{{ $card['title'] ?? '' }}">
										</div>
										<div class="form-group">
											<label class="fs-12 fw-600">{{ translate('Description') }}</label>
											<textarea class="form-control" maxlength="500" rows="3" name="terms_cards[{{ $index }}][description]">{{ $card['description'] ?? '' }}</textarea>
										</div>
										<div class="form-group mb-0">
											<label class="fs-12 fw-600">{{ translate('Link') }}</label>
											<input type="text" class="form-control" maxlength="191" placeholder="https://" name="terms_cards[{{ $index }}][link]" value="{{ $card['link'] ?? '' }}">
										</div>
									</div>
								</div>
								<button type="button" class="btn btn-icon btn-circle btn-sm btn-soft-danger position-absolute" style="right: 10px; top: 10px;" data-toggle="remove-parent" data-parent=".terms-card-row">
									<i class="las la-times"></i>
								</button>
							</div>
						@endforeach
					</div>

					<button type="button" id="add-terms-card" class="btn btn-soft-secondary btn-sm">
						<i class="las la-plus mr-1"></i>{{ translate('Add Card') }}
					</button>
				</div>

				<div class="border-top pt-4 mt-4">
					<h6 class="fw-600 mb-1">{{ translate('Terms Page Colors') }}</h6>
					<p class="text-muted fs-12 mb-3">{{ translate('Customize the Terms page without changing the colors of other pages.') }}</p>
					<div class="row gutters-10">
						@foreach([
							'hero_start' => translate('Hero Gradient Start'),
							'hero_end' => translate('Hero Gradient End'),
							'hero_text' => translate('Hero Text'),
							'accent' => translate('Accent'),
							'card_background' => translate('Card Background'),
							'heading' => translate('Heading'),
							'text' => translate('Body Text'),
						] as $colorKey => $colorLabel)
							<div class="col-md-6 col-lg-4">
								<div class="form-group">
									<label class="fs-12 fw-600">{{ $colorLabel }}</label>
									<div class="input-group">
										<input type="text" class="form-control aiz-color-input" name="terms_colors[{{ $colorKey }}]"
											value="{{ $termsColors[$colorKey] }}" pattern="^#[0-9a-fA-F]{6}$" required>
										<div class="input-group-append">
											<span class="input-group-text p-0">
												<input class="aiz-color-picker border-0 size-40px" type="color" value="{{ $termsColors[$colorKey] }}">
											</span>
										</div>
									</div>
								</div>
							</div>
						@endforeach
					</div>
				</div>
			@endif
		</div>

		<div class="card-header px-0">
			<h6 class="fw-600 mb-0">{{ translate('Seo Fields') }}</h6>
		</div>
		<div class="card-body px-0">

			<div class="form-group row">
				<label class="col-sm-2 col-from-label" for="name">{{translate('Meta Title')}}</label>
				<div class="col-sm-10">
					<input type="text" class="form-control" placeholder="{{translate('Title')}}" name="meta_title" value="{{ $page->meta_title }}">
				</div>
			</div>

			<div class="form-group row">
				<label class="col-sm-2 col-from-label" for="name">{{translate('Meta Description')}}</label>
				<div class="col-sm-10">
					<textarea class="resize-off form-control" placeholder="{{translate('Description')}}" name="meta_description">{!! $page->meta_description !!}</textarea>
				</div>
			</div>

			<div class="form-group row">
				<label class="col-sm-2 col-from-label" for="name">{{translate('Keywords')}}</label>
				<div class="col-sm-10">
					<textarea class="resize-off form-control" placeholder="{{translate('Keyword, Keyword')}}" name="keywords">{!! $page->keywords !!}</textarea>
					<small class="text-muted">{{ translate('Separate with coma') }}</small>
				</div>
			</div>

			<div class="form-group row">
				<label class="col-sm-2 col-from-label" for="name">{{translate('Meta Image')}}</label>
				<div class="col-sm-10">
					<div class="input-group " data-toggle="aizuploader" data-type="image">
							<div class="input-group-prepend">
								<div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse') }}</div>
						</div>
						<div class="form-control file-amount">{{ translate('Choose File') }}</div>
						<input type="hidden" name="meta_image" class="selected-files" value="{{ $page->meta_image }}">
					</div>
					<div class="file-preview">
					</div>
				</div>
			</div>

			<div class="text-right">
				<button type="submit" class="btn btn-primary">{{ translate('Update Page') }}</button>
			</div>
		</div>
	</form>
</div>
@endsection

@if($page->type == 'terms_conditions_page')
	@section('script')
		<script>
			(function () {
				var nextTermsCardIndex = {{ count($termsCards) }};

				$('#add-terms-card').on('click', function () {
					if ($('.terms-card-row').length >= 12) {
						AIZ.plugins.notify('warning', '{{ translate('You can add a maximum of 12 cards.') }}');
						return;
					}

					var index = nextTermsCardIndex++;
					var row = `
						<div class="terms-card-row remove-parent border border-dashed rounded p-3 mb-3 position-relative">
							<div class="row gutters-10">
								<div class="col-md-4">
									<label class="fs-12 fw-600">{{ translate('Image') }}</label>
									<div class="input-group" data-toggle="aizuploader" data-type="image" data-multiple="false">
										<div class="input-group-prepend"><div class="input-group-text bg-soft-secondary">{{ translate('Browse') }}</div></div>
										<div class="form-control file-amount">{{ translate('Choose File') }}</div>
										<input type="hidden" name="terms_cards[${index}][image]" class="selected-files">
									</div>
									<div class="file-preview box sm"></div>
								</div>
								<div class="col-md-8">
									<div class="form-group"><label class="fs-12 fw-600">{{ translate('Title') }}</label><input type="text" class="form-control" maxlength="100" name="terms_cards[${index}][title]"></div>
									<div class="form-group"><label class="fs-12 fw-600">{{ translate('Description') }}</label><textarea class="form-control" maxlength="500" rows="3" name="terms_cards[${index}][description]"></textarea></div>
									<div class="form-group mb-0"><label class="fs-12 fw-600">{{ translate('Link') }}</label><input type="text" class="form-control" maxlength="191" placeholder="https://" name="terms_cards[${index}][link]"></div>
								</div>
							</div>
							<button type="button" class="btn btn-icon btn-circle btn-sm btn-soft-danger position-absolute" style="right: 10px; top: 10px;" data-toggle="remove-parent" data-parent=".terms-card-row"><i class="las la-times"></i></button>
						</div>`;

					$('.terms-cards-target').append(row);
				});
			})();
		</script>
	@endsection
@endif

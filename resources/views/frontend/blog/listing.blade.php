@extends('frontend.layouts.app')

@section('meta_title'){{ translate('Blog') }}@stop

@section('content')
    @php
        $featuredBlog = $blogs->first();
        $postBlogs = $blogs->skip(1);
    @endphp

    <style>
        .uui-blog-page {
            --uui-ink: #101828;
            --uui-muted: #475467;
            --uui-subtle: #667085;
            --uui-border: #eaecf0;
            --uui-soft: #f9fafb;
            --uui-primary: var(--primary, #7f56d9);
            color: var(--uui-ink);
            background: #fff;
        }

        .uui-blog-page a:hover { text-decoration: none; }

        .uui-blog-hero {
            padding: 88px 0 64px;
            text-align: center;
        }

        .uui-blog-eyebrow {
            margin-bottom: 12px;
            color: var(--uui-primary);
            font-size: 16px;
            font-weight: 600;
        }

        .uui-blog-title {
            max-width: 820px;
            margin: 0 auto 20px;
            color: var(--uui-ink);
            font-size: clamp(38px, 5vw, 56px);
            font-weight: 700;
            letter-spacing: -0.04em;
            line-height: 1.12;
        }

        .uui-blog-intro {
            max-width: 680px;
            margin: 0 auto;
            color: var(--uui-muted);
            font-size: 20px;
            line-height: 1.5;
        }

        .uui-blog-search {
            position: relative;
            width: min(100%, 360px);
            margin: 32px auto 0;
        }

        .uui-blog-search i {
            position: absolute;
            top: 50%;
            left: 16px;
            color: var(--uui-subtle);
            font-size: 20px;
            transform: translateY(-50%);
        }

        .uui-blog-search input {
            width: 100%;
            height: 48px;
            padding: 10px 44px;
            border: 1px solid #d0d5dd;
            border-radius: 8px;
            color: var(--uui-ink);
            font-size: 16px;
            box-shadow: 0 1px 2px rgba(16, 24, 40, .05);
        }

        .uui-blog-search input:focus {
            border-color: var(--uui-primary);
            outline: 0;
            box-shadow: 0 0 0 4px rgba(127, 86, 217, .12);
        }

        .uui-blog-filters {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 8px;
            margin-bottom: 64px;
        }

        .uui-blog-filter {
            display: inline-flex;
            align-items: center;
            min-height: 40px;
            padding: 8px 14px;
            border: 1px solid transparent;
            border-radius: 8px;
            color: var(--uui-muted);
            font-size: 14px;
            font-weight: 600;
            transition: .2s ease;
        }

        .uui-blog-filter:hover,
        .uui-blog-filter.is-active {
            color: var(--uui-primary);
            background: color-mix(in srgb, var(--uui-primary) 9%, white);
        }

        .uui-blog-featured {
            display: grid;
            grid-template-columns: minmax(0, 1.45fr) minmax(320px, .85fr);
            overflow: hidden;
            margin-bottom: 96px;
            border: 1px solid var(--uui-border);
            border-radius: 16px;
            background: #fff;
            box-shadow: 0 2px 4px rgba(16, 24, 40, .04), 0 8px 20px rgba(16, 24, 40, .04);
        }

        .uui-blog-featured-image {
            display: block;
            min-height: 430px;
            overflow: hidden;
        }

        .uui-blog-featured-image img,
        .uui-blog-card-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform .45s ease;
        }

        .uui-blog-featured:hover img,
        .uui-blog-card:hover .uui-blog-card-image img { transform: scale(1.035); }

        .uui-blog-featured-copy {
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 48px;
        }

        .uui-blog-meta {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 8px;
            margin-bottom: 14px;
            color: var(--uui-primary);
            font-size: 14px;
            font-weight: 600;
        }

        .uui-blog-meta-separator {
            width: 3px;
            height: 3px;
            border-radius: 50%;
            background: #98a2b3;
        }

        .uui-blog-featured h2,
        .uui-blog-card h3 {
            color: var(--uui-ink);
            letter-spacing: -.025em;
        }

        .uui-blog-featured h2 {
            margin: 0 0 16px;
            font-size: clamp(28px, 3vw, 36px);
            font-weight: 700;
            line-height: 1.25;
        }

        .uui-blog-summary {
            margin: 0;
            color: var(--uui-muted);
            font-size: 16px;
            line-height: 1.55;
        }

        .uui-blog-read-more {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-top: 30px;
            color: var(--uui-primary);
            font-size: 16px;
            font-weight: 600;
        }

        .uui-blog-read-more i {
            transform: rotate(45deg);
            transition: transform .2s ease;
        }
        .uui-blog-read-more:hover i { transform: rotate(45deg) translate(3px, -3px); }

        .uui-blog-section-head {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            gap: 24px;
            margin-bottom: 32px;
            padding-bottom: 20px;
            border-bottom: 1px solid var(--uui-border);
        }

        .uui-blog-section-head h2 {
            margin: 0;
            color: var(--uui-ink);
            font-size: 30px;
            font-weight: 700;
            letter-spacing: -.025em;
        }

        .uui-blog-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 48px 32px;
        }

        .uui-blog-card { min-width: 0; }

        .uui-blog-card-image {
            display: block;
            overflow: hidden;
            aspect-ratio: 16 / 10;
            margin-bottom: 24px;
            border-radius: 12px;
            background: var(--uui-soft);
        }

        .uui-blog-card h3 {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 12px;
            margin: 0 0 12px;
            font-size: 22px;
            font-weight: 700;
            line-height: 1.35;
        }

        .uui-blog-card h3 a { color: inherit; }
        .uui-blog-card h3 i { flex: 0 0 auto; margin-top: 3px; font-size: 20px; }

        .uui-blog-card .uui-blog-summary {
            display: -webkit-box;
            overflow: hidden;
            -webkit-box-orient: vertical;
            -webkit-line-clamp: 2;
        }

        .uui-blog-pagination {
            margin-top: 56px;
            padding-top: 20px;
            border-top: 1px solid var(--uui-border);
        }

        .uui-blog-pagination .pagination { justify-content: center; }
        .uui-blog-pagination .pagination .page-link {
            border: 0;
            border-radius: 8px !important;
            color: var(--uui-muted);
        }

        .uui-blog-pagination .pagination .active .page-link {
            color: var(--uui-primary);
            background: color-mix(in srgb, var(--uui-primary) 9%, white);
        }

        .uui-blog-empty {
            padding: 72px 24px;
            border: 1px solid var(--uui-border);
            border-radius: 16px;
            text-align: center;
            background: var(--uui-soft);
        }

        .uui-blog-empty i { color: var(--uui-subtle); font-size: 42px; }
        .uui-blog-empty h2 { margin: 16px 0 8px; font-size: 24px; }
        .uui-blog-empty p { margin: 0; color: var(--uui-muted); }

        .uui-blog-cta {
            margin-top: 96px;
            padding: 64px;
            border-radius: 16px;
            background: var(--uui-soft);
            text-align: center;
        }

        .uui-blog-cta h2 {
            margin: 0 0 12px;
            color: var(--uui-ink);
            font-size: 30px;
            font-weight: 700;
            letter-spacing: -.025em;
        }

        .uui-blog-cta p { margin: 0; color: var(--uui-muted); font-size: 18px; }

        .uui-blog-subscribe {
            display: flex;
            width: min(100%, 480px);
            gap: 12px;
            margin: 28px auto 0;
        }

        .uui-blog-subscribe input {
            flex: 1 1 auto;
            min-width: 0;
            height: 48px;
            padding: 10px 14px;
            border: 1px solid #d0d5dd;
            border-radius: 8px;
            font-size: 16px;
            box-shadow: 0 1px 2px rgba(16, 24, 40, .05);
        }

        .uui-blog-subscribe button {
            height: 48px;
            padding: 0 20px;
            border: 0;
            border-radius: 8px;
            color: #fff;
            background: var(--uui-primary);
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
        }

        @media (max-width: 991.98px) {
            .uui-blog-hero { padding: 64px 0 48px; }
            .uui-blog-filters { margin-bottom: 48px; }
            .uui-blog-featured { grid-template-columns: 1fr; margin-bottom: 72px; }
            .uui-blog-featured-image { min-height: 380px; }
            .uui-blog-featured-copy { padding: 36px; }
            .uui-blog-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        }

        @media (max-width: 575.98px) {
            .uui-blog-hero { padding: 48px 0 40px; }
            .uui-blog-intro { font-size: 17px; }
            .uui-blog-filters {
                flex-wrap: nowrap;
                justify-content: flex-start;
                overflow-x: auto;
                margin-right: -15px;
                margin-left: -15px;
                padding: 0 15px 8px;
            }
            .uui-blog-filter { white-space: nowrap; }
            .uui-blog-featured { border-radius: 12px; }
            .uui-blog-featured-image { min-height: 250px; }
            .uui-blog-featured-copy { padding: 24px; }
            .uui-blog-grid { grid-template-columns: 1fr; gap: 40px; }
            .uui-blog-section-head { align-items: flex-start; flex-direction: column; }
            .uui-blog-cta { margin-top: 72px; padding: 40px 20px; }
            .uui-blog-subscribe { flex-direction: column; }
            .uui-blog-subscribe button { width: 100%; }
        }
    </style>

    <main class="uui-blog-page">
        <header class="uui-blog-hero">
            <div class="container">
                <div class="uui-blog-eyebrow">{{ translate('Our blog') }}</div>
                <h1 class="uui-blog-title">{{ translate('Resources and insights') }}</h1>
                <p class="uui-blog-intro">
                    {{ translate('The latest industry news, interviews, technologies, and resources.') }}
                </p>

                <form class="uui-blog-search" action="{{ route('blog') }}" method="GET">
                    <i class="las la-search" aria-hidden="true"></i>
                    <input type="search" name="search" value="{{ $search }}"
                        placeholder="{{ translate('Search') }}" aria-label="{{ translate('Search blogs') }}">
                    @foreach ($selected_categories as $selectedCategory)
                        <input type="hidden" name="selected_categories[]" value="{{ $selectedCategory }}">
                    @endforeach
                </form>
            </div>
        </header>

        <div class="container pb-5">
            <nav class="uui-blog-filters" aria-label="{{ translate('Blog categories') }}">
                <a href="{{ route('blog', array_filter(['search' => $search])) }}"
                    class="uui-blog-filter {{ empty($selected_categories) ? 'is-active' : '' }}">
                    {{ translate('View all') }}
                </a>
                @foreach (get_all_blog_categories() as $category)
                    <a href="{{ route('blog', array_filter(['search' => $search, 'selected_categories' => [$category->slug]])) }}"
                        class="uui-blog-filter {{ in_array($category->slug, $selected_categories) ? 'is-active' : '' }}">
                        {{ $category->category_name }}
                    </a>
                @endforeach
            </nav>

            @if ($featuredBlog)
                <article class="uui-blog-featured">
                    <a href="{{ route('blog.details', $featuredBlog->slug) }}" class="uui-blog-featured-image">
                        <img src="{{ static_asset('assets/img/placeholder-rect.jpg') }}"
                            data-src="{{ uploaded_asset($featuredBlog->banner) }}"
                            alt="{{ $featuredBlog->title }}" class="lazyload">
                    </a>
                    <div class="uui-blog-featured-copy">
                        <div class="uui-blog-meta">
                            @if ($featuredBlog->category)
                                <span>{{ $featuredBlog->category->category_name }}</span>
                                <span class="uui-blog-meta-separator" aria-hidden="true"></span>
                            @endif
                            <time datetime="{{ $featuredBlog->created_at->toDateString() }}">
                                {{ $featuredBlog->created_at->format('d M Y') }}
                            </time>
                        </div>
                        <h2>
                            <a href="{{ route('blog.details', $featuredBlog->slug) }}" class="text-reset">
                                {{ $featuredBlog->title }}
                            </a>
                        </h2>
                        <p class="uui-blog-summary">{{ $featuredBlog->short_description }}</p>
                        <a href="{{ route('blog.details', $featuredBlog->slug) }}" class="uui-blog-read-more">
                            {{ translate('Read article') }}
                            <i class="las la-arrow-up" aria-hidden="true"></i>
                        </a>
                    </div>
                </article>

                @if ($postBlogs->isNotEmpty())
                    <section aria-labelledby="latest-blog-posts">
                        <div class="uui-blog-section-head">
                            <div>
                                <div class="uui-blog-eyebrow mb-2">{{ translate('Latest updates') }}</div>
                                <h2 id="latest-blog-posts">{{ translate('Latest blog posts') }}</h2>
                            </div>
                        </div>

                        <div class="uui-blog-grid">
                            @foreach ($postBlogs as $blog)
                                <article class="uui-blog-card">
                                    <a href="{{ route('blog.details', $blog->slug) }}" class="uui-blog-card-image">
                                        <img src="{{ static_asset('assets/img/placeholder-rect.jpg') }}"
                                            data-src="{{ uploaded_asset($blog->banner) }}"
                                            alt="{{ $blog->title }}" class="lazyload">
                                    </a>
                                    <div class="uui-blog-meta">
                                        @if ($blog->category)
                                            <span>{{ $blog->category->category_name }}</span>
                                            <span class="uui-blog-meta-separator" aria-hidden="true"></span>
                                        @endif
                                        <time datetime="{{ $blog->created_at->toDateString() }}">
                                            {{ $blog->created_at->format('d M Y') }}
                                        </time>
                                    </div>
                                    <h3>
                                        <a href="{{ route('blog.details', $blog->slug) }}">{{ $blog->title }}</a>
                                        <i class="las la-arrow-up" style="transform: rotate(45deg);" aria-hidden="true"></i>
                                    </h3>
                                    <p class="uui-blog-summary">{{ $blog->short_description }}</p>
                                </article>
                            @endforeach
                        </div>
                    </section>
                @endif

                <div class="uui-blog-pagination">
                    {{ $blogs->appends(request()->query())->links() }}
                </div>
            @else
                <div class="uui-blog-empty">
                    <i class="las la-search" aria-hidden="true"></i>
                    <h2>{{ translate('No blog posts found') }}</h2>
                    <p>{{ translate('Try changing your search or selecting another category.') }}</p>
                </div>
            @endif

            <section class="uui-blog-cta" aria-labelledby="blog-newsletter-title">
                <h2 id="blog-newsletter-title">{{ translate('Stay up to date') }}</h2>
                <p>{{ translate('Get the latest stories and resources delivered to your inbox.') }}</p>
                <form class="uui-blog-subscribe" method="POST" action="{{ route('subscribers.store') }}">
                    @csrf
                    <input type="email" name="email" required
                        placeholder="{{ translate('Enter your email') }}"
                        aria-label="{{ translate('Email address') }}">
                    <button type="submit">{{ translate('Subscribe') }}</button>
                </form>
            </section>
        </div>
    </main>
@endsection

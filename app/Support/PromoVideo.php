<?php

namespace App\Support;

use Illuminate\Support\Facades\Http;

class PromoVideo
{
    private const TIKTOK_SHORT_HOSTS = ['vm.tiktok.com', 'vt.tiktok.com'];

    /**
     * Turn a pasted Facebook / YouTube / TikTok link into embed data, or null if unsupported.
     *
     * @return array{platform:string,embed_id:string,vertical:bool}|null
     */
    public static function parse(string $url): ?array
    {
        $url = trim($url);
        $parts = parse_url($url);

        if (!$parts || !in_array(strtolower($parts['scheme'] ?? ''), ['http', 'https'], true) || empty($parts['host'])) {
            return null;
        }

        $host = preg_replace('/^(www\.|m\.|web\.)/', '', strtolower($parts['host']));
        $path = $parts['path'] ?? '';
        parse_str($parts['query'] ?? '', $query);

        if (in_array($host, ['youtube.com', 'music.youtube.com', 'youtube-nocookie.com', 'youtu.be'], true)) {
            $id = null;
            $vertical = false;

            if ($host === 'youtu.be') {
                $id = explode('/', trim($path, '/'))[0] ?? null;
            } elseif (preg_match('#^/(shorts|embed|live|v)/([A-Za-z0-9_-]{6,15})#', $path, $m)) {
                $id = $m[2];
                $vertical = $m[1] === 'shorts';
            } elseif (!empty($query['v']) && is_string($query['v'])) {
                $id = $query['v'];
            }

            if ($id && preg_match('/^[A-Za-z0-9_-]{6,15}$/', $id)) {
                return ['platform' => 'youtube', 'embed_id' => $id, 'vertical' => $vertical];
            }

            return null;
        }

        if ($host === 'tiktok.com' || str_ends_with($host, '.tiktok.com')) {
            if (in_array($host, self::TIKTOK_SHORT_HOSTS, true) || preg_match('#^/t/#', $path)) {
                $resolved = self::resolveTikTokShortLink($url);
                if (!$resolved) {
                    return null;
                }
                $path = parse_url($resolved, PHP_URL_PATH) ?: '';
            }

            if (preg_match('#/video/(\d{8,25})#', $path, $m)) {
                return ['platform' => 'tiktok', 'embed_id' => $m[1], 'vertical' => true];
            }

            return null;
        }

        if (in_array($host, ['facebook.com', 'fb.watch', 'fb.com'], true)) {
            // App "Copy link" URLs (/share/r/…, fb.watch/…) can't be embedded until resolved to the real video URL.
            $canonical = self::canonicalFacebook($url);
            if (!$canonical && ($host === 'fb.watch' || preg_match('#^/share/(r|v|p|reel|video)/#i', $path))) {
                $canonical = self::resolveFacebookLink($url);
            }
            if ($canonical) {
                $url = $canonical;
                $parts = parse_url($url);
                $path = $parts['path'] ?? '';
                parse_str($parts['query'] ?? '', $query);
            }

            $looksLikeVideo = preg_match('#/(videos?|reel|reels|watch)/?#i', $path)
                || isset($query['v']);

            if (!$looksLikeVideo) {
                return null;
            }

            return [
                'platform' => 'facebook',
                'embed_id' => $url,
                'vertical' => (bool) preg_match('#/(reel|reels)/#i', $path),
            ];
        }

        return null;
    }

    /** Build the iframe URL for a stored video (autoplay + muted by default; the admin preview turns autoplay off). */
    public static function embedUrl(array $video, bool $autoplay = true): ?string
    {
        $id = $video['embed_id'] ?? null;
        if (!$id) {
            return null;
        }

        switch ($video['platform'] ?? null) {
            case 'youtube':
                $url = 'https://www.youtube-nocookie.com/embed/' . rawurlencode($id) . '?playsinline=1&rel=0&modestbranding=1';
                return $autoplay
                    ? $url . '&autoplay=1&mute=1&loop=1&playlist=' . rawurlencode($id)
                    : $url;
            case 'tiktok':
                return 'https://www.tiktok.com/player/v1/' . rawurlencode($id)
                    . '?loop=1&music_info=0&description=0&rel=0&autoplay=' . ($autoplay ? 1 : 0);
            case 'facebook':
                return 'https://www.facebook.com/plugins/video.php?href=' . rawurlencode($id)
                    . '&show_text=false&allowfullscreen=true'
                    . ($autoplay ? '&autoplay=true&mute=1&muted=1' : '&autoplay=false');
        }

        return null;
    }

    public static function thumbnailUrl(array $video): ?string
    {
        if (($video['platform'] ?? null) === 'youtube' && !empty($video['embed_id'])) {
            return 'https://i.ytimg.com/vi/' . rawurlencode($video['embed_id']) . '/hqdefault.jpg';
        }

        return null;
    }

    /** Clean canonical Facebook video URL from a URL that already points at a video, or null. */
    private static function canonicalFacebook(string $url): ?string
    {
        $path = parse_url($url, PHP_URL_PATH) ?: '';
        parse_str((string) parse_url($url, PHP_URL_QUERY), $q);

        if (preg_match('#^/(reel|reels)/(\d+)#i', $path, $m)) {
            return 'https://www.facebook.com/reel/' . $m[2] . '/';
        }
        if (preg_match('#^(/[^/]+)?/videos?/(\d+)#i', $path, $m)) {
            return 'https://www.facebook.com' . $m[1] . '/videos/' . $m[2] . '/';
        }
        if (preg_match('#^/watch/?$#i', $path) && !empty($q['v']) && preg_match('/^\d+$/', (string) $q['v'])) {
            return 'https://www.facebook.com/watch/?v=' . $q['v'];
        }

        return null;
    }

    /** Follow Facebook share redirects and return a clean canonical video URL, or null. */
    private static function resolveFacebookLink(string $url): ?string
    {
        try {
            for ($hop = 0; $hop < 4; $hop++) {
                $host = strtolower((string) parse_url($url, PHP_URL_HOST));
                $allowed = in_array($host, ['fb.watch', 'fb.com'], true)
                    || $host === 'facebook.com' || str_ends_with($host, '.facebook.com');
                if (!$allowed) {
                    return null;
                }

                if ($canonical = self::canonicalFacebook($url)) {
                    return $canonical;
                }

                $response = Http::timeout(6)
                    ->withoutRedirecting()
                    ->withHeaders(['User-Agent' => 'Mozilla/5.0 (compatible; KneayerngBot/1.0)'])
                    ->get($url);

                $location = $response->header('Location');
                if (!$location) {
                    return null;
                }
                $url = str_starts_with($location, '/') ? 'https://www.facebook.com' . $location : $location;
            }
        } catch (\Throwable $e) {
            return null;
        }

        return null;
    }

    private static function resolveTikTokShortLink(string $url): ?string
    {
        try {
            for ($hop = 0; $hop < 3; $hop++) {
                $host = strtolower((string) parse_url($url, PHP_URL_HOST));
                if ($host !== 'tiktok.com' && !str_ends_with($host, '.tiktok.com')) {
                    return null;
                }

                $response = Http::timeout(5)
                    ->withoutRedirecting()
                    ->withHeaders(['User-Agent' => 'Mozilla/5.0 (compatible; KneayerngBot/1.0)'])
                    ->get($url);

                $location = $response->header('Location');
                if (!$location) {
                    return preg_match('#/video/\d+#', $url) ? $url : null;
                }

                $url = $location;
                if (preg_match('#/video/\d+#', $url)) {
                    return $url;
                }
            }
        } catch (\Throwable $e) {
            return null;
        }

        return null;
    }
}

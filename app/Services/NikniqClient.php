<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class NikniqClient
{
    /**
     * Fetch latest feed items from nikniq.com (tries common feed endpoints).
     * Returns an array of ['title','link','date','excerpt'] entries.
     */
    public function fetchLatest(int $limit = 10): array
    {
        $candidates = [
            'https://nikniq.com/feed',
            'https://nikniq.com/rss',
            'https://nikniq.com/rss.xml',
            'https://nikniq.com/feed.xml',
        ];

        foreach ($candidates as $url) {
            try {
                $res = Http::timeout(10)->get($url);
            } catch (\Exception $e) {
                continue;
            }

            if (! $res->successful()) {
                continue;
            }

            $body = (string) $res->body();

            if (stripos($body, '<rss') !== false || stripos($body, '<feed') !== false) {
                $items = $this->parseFeed($body, $limit);
                if (! empty($items)) {
                    return $items;
                }
            }
        }

        return [];
    }

    protected function parseFeed(string $xml, int $limit): array
    {
        libxml_use_internal_errors(true);
        $doc = @simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA);
        if (! $doc) {
            return [];
        }

        $items = [];

        if (isset($doc->channel->item)) {
            foreach ($doc->channel->item as $i) {
                if (count($items) >= $limit) break;
                $title = (string) ($i->title ?? '');
                $link = (string) ($i->link ?? ($i->guid ?? ''));
                $date = (string) ($i->pubDate ?? '');
                $desc = (string) ($i->description ?? ($i->{'content:encoded'} ?? ''));
                $items[] = $this->mapItem($title, $link, $date, $desc);
            }
        } elseif (isset($doc->entry)) {
            foreach ($doc->entry as $i) {
                if (count($items) >= $limit) break;
                $title = (string) ($i->title ?? '');
                $link = '';
                if (isset($i->link) && isset($i->link['href'])) {
                    $link = (string) $i->link['href'];
                } else {
                    $link = (string) ($i->id ?? '');
                }
                $date = (string) ($i->updated ?? $i->published ?? '');
                $desc = (string) ($i->summary ?? $i->content ?? '');
                $items[] = $this->mapItem($title, $link, $date, $desc);
            }
        }

        return $items;
    }

    protected function mapItem(string $title, string $link, string $date, string $desc): array
    {
        $excerpt = trim(strip_tags(html_entity_decode($desc)));
        $excerpt = preg_replace('/\s+/', ' ', $excerpt);
        if (Str::length($excerpt) > 220) {
            $excerpt = Str::substr($excerpt, 0, 217) . '...';
        }

        return [
            'title' => $title,
            'link' => $link,
            'date' => $date,
            'excerpt' => $excerpt,
        ];
    }
}

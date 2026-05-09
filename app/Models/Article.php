<?php

namespace App\Models;

use App\Mail\NewArticlePublishedMail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Article extends Model
{
    use HasFactory;
    use HasSlug;

    protected $fillable = [
        'category_id',
        'journalist_id',
        'title',
        'slug',
        'excerpt',
        'content',
        'featured_image',
        'video_url',
        'is_headline',
        'view_count',
        'status',
        'published_at',
        'notified_subscribers_at',
    ];

    protected function casts(): array
    {
        return [
            'is_headline' => 'boolean',
            'published_at' => 'datetime',
            'notified_subscribers_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        // Invalidate homepage & stats cache setiap kali artikel berubah atau dihapus
        static::deleted(function (): void {
            Cache::forget('homepage_data');
            Cache::forget('admin_stats_overview');
        });

        static::saved(function (self $article): void {
            // Selalu clear cache saat artikel disimpan
            Cache::forget('homepage_data');
            Cache::forget('admin_stats_overview');

            // Kirim notif subscriber jika baru dipublikasikan
            $shouldNotify = $article->status === 'published'
                && $article->published_at?->isPast()
                && ! $article->notified_subscribers_at;

            if (! $shouldNotify) {
                return;
            }

            Subscription::query()
                ->where('is_active', true)
                ->select(['id', 'email', 'token'])
                ->chunk(100, function ($subscriptions) use ($article): void {
                    foreach ($subscriptions as $subscription) {
                        Mail::to($subscription->email)->queue(new NewArticlePublishedMail($article, $subscription));
                    }
                });

            $article->forceFill([
                'notified_subscribers_at' => now(),
            ])->saveQuietly();
        });
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function journalist(): BelongsTo
    {
        return $this->belongsTo(Journalist::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query
            ->where('status', 'published')
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}

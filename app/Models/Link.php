<?php

namespace App\Models;

use Database\Factories\LinkFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Link extends Model
{
    /** @use HasFactory<LinkFactory> */
    use HasFactory;

    /**
     * @var list<string>
     */
    protected $fillable = ['code', 'url', 'visits'];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'visits' => 'integer',
            'last_visited_at' => 'datetime',
        ];
    }

    /**
     * Use the short code (not the id) in route model binding.
     */
    public function getRouteKeyName(): string
    {
        return 'code';
    }

    /**
     * The full short URL for this link.
     */
    public function shortUrl(): string
    {
        return url('/'.$this->code);
    }

    /**
     * Generate a short code that is not already taken.
     */
    public static function newCode(int $length = 7): string
    {
        do {
            $code = Str::lower(Str::random($length));
        } while (static::query()->where('code', $code)->exists());

        return $code;
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WpPost extends Model
{
    protected $connection = 'wordpress';
    protected $table = 'posts';
    protected $primaryKey = 'ID';
    public $timestamps = false;

    protected $casts = [
        'post_date' => 'datetime',
    ];

    protected $fillable = [
        'post_author', 'post_date', 'post_content', 'post_title', 'post_excerpt', 'post_status', 'post_name', 'post_type',
    ];

    public function getExcerptAttribute()
    {
        if (! empty($this->post_excerpt)) {
            return $this->post_excerpt;
        }

        $text = strip_tags($this->post_content);
        $text = html_entity_decode($text);
        $text = preg_replace('/\s+/', ' ', $text);

        return strlen($text) > 200 ? substr($text, 0, 197).'...' : $text;
    }
}

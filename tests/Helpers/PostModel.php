<?php

namespace Saucebase\LaravelPlaywright\Tests\Helpers;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $title
 * @property string $created_at
 * @property string $updated_at
 */
class PostModel extends Model
{

    /**
     * @use HasFactory<PostFactory>
     */
    use HasFactory;

    protected $table = 'posts';

    public static function newFactory(): PostFactory
    {
        return new PostFactory();
    }

}

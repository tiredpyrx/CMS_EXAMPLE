<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class File extends Model
{
    use HasFactory;

    public const FRONTED_DATAS = ['title', 'description', 'source'];

    protected $fillable = [
        'user_id',
        'category_id',
        'field_id',
        'file_id',
        'title',
        'description',
        'source',
        'handler'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function field(): BelongsTo
    {
        return $this->belongsTo(Field::class);
    }

    public function child(): HasOne
    {
        return $this->hasOne(File::class);
    }

    public static function getFrontedDatas()
    {
        return File::FRONTED_DATAS;
    }

    public function getOnlyFrontedDatas()
    {
        return $this->only($this::getFrontedDatas());
    }
}

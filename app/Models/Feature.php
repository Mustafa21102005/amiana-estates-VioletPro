<?php

namespace App\Models;

use App\Models\Concerns\HasOrderedPosition;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['property_type_id', 'title', 'category_id', 'sort_order'])]
class Feature extends Model
{
    /** @use HasFactory<\Database\Factories\FeatureFactory> */
    use HasFactory, HasOrderedPosition;

    /** A feature's position counts within its own property type and category. */
    protected function orderGroup(): array
    {
        return [
            'property_type_id' => $this->property_type_id,
            'category_id' => $this->category_id,
        ];
    }

    public function propertyType(): BelongsTo
    {
        return $this->belongsTo(PropertyType::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}

<?php

namespace App\Models\Concerns;

use Illuminate\Support\Facades\DB;

/**
 * Keeps a `sort_order` column as a gap-free sequence: 1, 2, 3...
 * Models can scope the sequence to a group (e.g. per category) by overriding orderGroup().
 *
 * @property int $sort_order
 */
trait HasOrderedPosition
{
    /** Columns that define which records are ordered together. Empty = the whole table. */
    protected function orderGroup(): array
    {
        return [];
    }

    /** The position a new record gets by default: the end of its group. */
    public static function nextOrder(array $group = []): int
    {
        return (int) static::where($group)->max('sort_order') + 1;
    }

    /** Keep a requested position between 1 and $max so the sequence never has gaps. */
    public static function clampOrder(int $order, int $max): int
    {
        return max(1, min($order, $max));
    }

    /** Open a gap at $order by pushing it and everything after it down by one. */
    public static function makeRoomAtOrder(int $order, array $group = []): void
    {
        static::where($group)->where('sort_order', '>=', $order)->increment('sort_order');
    }

    /** Move this record to $newOrder and shift the ones in between. */
    public function moveToOrder(int $newOrder): void
    {
        DB::transaction(function () use ($newOrder) {
            $group = $this->orderGroup();
            $oldOrder = (int) $this->sort_order;
            $newOrder = static::clampOrder($newOrder, static::where($group)->count());

            if ($newOrder === $oldOrder) {
                return;
            }

            $others = static::where($group)->whereKeyNot($this->getKey());

            if ($newOrder > $oldOrder) {
                $others->whereBetween('sort_order', [$oldOrder + 1, $newOrder])->decrement('sort_order');
            } else {
                $others->whereBetween('sort_order', [$newOrder, $oldOrder - 1])->increment('sort_order');
            }

            $this->sort_order = $newOrder;
            $this->save();
        });
    }

    /** Renumber a group 1..n in its current order, closing any gaps. */
    public static function reorder(array $group = []): void
    {
        DB::transaction(function () use ($group) {
            static::where($group)
                ->orderBy('sort_order')
                ->orderBy('id')
                ->get()
                ->each(fn($item, $index) => $item->update(['sort_order' => $index + 1]));
        });
    }
}

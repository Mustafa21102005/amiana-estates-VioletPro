<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['phone', 'email', 'body'])]
class Message extends Model
{
    /** @use HasFactory<\Database\Factories\MessageFactory> */
    use HasFactory;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return ['read_at' => 'datetime'];
    }

    /** An unread message older than this many days counts as overdue. */
    public const OVERDUE_DAYS = 15;

    public function scopeOverdue(Builder $query): void
    {
        $query->whereNull('read_at')
            ->where('created_at', '<', now()->subDays(self::OVERDUE_DAYS));
    }

    public function scopeUnread(Builder $query): void
    {
        $query->whereNull('read_at');
    }

    public function isRead(): bool
    {
        return $this->read_at !== null;
    }

    // read_at is not fillable, so a visitor's form can never mark its own message as read
    public function markAsRead(): void
    {
        if (! $this->isRead()) {
            $this->forceFill(['read_at' => now()])->save();
        }
    }

    public function markAsUnread(): void
    {
        $this->forceFill(['read_at' => null])->save();
    }

    /** "+232 76 123 456" becomes "tel:+23276123456" */
    public function phoneLink(): string
    {
        return 'tel:' . preg_replace('/[^\d+]/', '', $this->phone);
    }

    /**
     * The phone number in the form WhatsApp wants (digits only, country code first),
     * or null when the visitor did not give a country code.
     */
    public function whatsappNumber(): ?string
    {
        $phone = trim($this->phone);

        // No country code means no reliable link, so there is no button
        if (! str_starts_with($phone, '+')) {
            return null;
        }

        // "+44 (0)7911 123456": the (0) is how people write the local 0, which WhatsApp does not want
        $digits = preg_replace('/\D+/', '', str_replace('(0)', '', $phone));

        return strlen($digits) >= 8 && strlen($digits) <= 15 ? $digits : null;
    }

    public function whatsappLink(): ?string
    {
        $number = $this->whatsappNumber();

        if ($number === null) {
            return null;
        }

        $greeting = 'Hello, this is ' . config('app.name') . '. Thank you for your enquiry.';

        return "https://wa.me/{$number}?text=" . rawurlencode($greeting);
    }
}

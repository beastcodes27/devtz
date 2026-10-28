<?php

namespace App;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use JsonException;

class PortfolioInquiryStore
{
    private const DIRECTORY = 'portfolio-inquiries';

    /** @return Collection<int, array<string, mixed>> */
    public function all(): Collection
    {
        return collect(Storage::disk('local')->files(self::DIRECTORY))
            ->map(fn (string $path): ?array => $this->read($path))
            ->filter()
            ->sortByDesc('submitted_at')
            ->values();
    }

    /** @return array<string, mixed>|null */
    public function find(string $id): ?array
    {
        if (! Str::isUuid($id)) {
            return null;
        }

        $path = $this->path($id);

        if (! Storage::disk('local')->exists($path)) {
            return null;
        }

        return $this->read($path);
    }

    /** @param array<string, mixed> $inquiry */
    public function store(array $inquiry): void
    {
        Storage::disk('local')->put(
            $this->path((string) $inquiry['id']),
            json_encode($inquiry, JSON_PRETTY_PRINT | JSON_THROW_ON_ERROR),
        );
    }

    /** @param array<string, mixed> $reply */
    public function addReply(string $inquiryId, array $reply): bool
    {
        $inquiry = $this->find($inquiryId);

        if ($inquiry === null) {
            return false;
        }

        $inquiry['replies'] = [...($inquiry['replies'] ?? []), $reply];
        $inquiry['last_replied_at'] = $reply['sent_at'];
        $this->store($inquiry);

        return true;
    }

    private function path(string $id): string
    {
        return self::DIRECTORY.'/'.$id.'.json';
    }

    /** @return array<string, mixed>|null */
    private function read(string $path): ?array
    {
        try {
            $inquiry = json_decode(
                Storage::disk('local')->get($path),
                true,
                flags: JSON_THROW_ON_ERROR,
            );

            return is_array($inquiry) ? $inquiry : null;
        } catch (JsonException) {
            return null;
        }
    }
}

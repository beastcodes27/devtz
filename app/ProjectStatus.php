<?php

namespace App;

enum ProjectStatus: string
{
    case Draft = 'draft';
    case Planning = 'planning';
    case InProgress = 'in-progress';
    case OnHold = 'on-hold';
    case Completed = 'completed';

    public function label(): string
    {
        return match ($this) {
            self::Draft => 'Draft',
            self::Planning => 'Planning',
            self::InProgress => 'In progress',
            self::OnHold => 'On hold',
            self::Completed => 'Completed',
        };
    }
}

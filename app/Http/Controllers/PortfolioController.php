<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\TeamMember;
use Illuminate\Contracts\View\View;

class PortfolioController extends Controller
{
    public function __invoke(): View
    {
        $projects = Project::featured()
            ->limit(6)
            ->get()
            ->values()
            ->map(fn (Project $project, int $index): array => [
                'index' => str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT),
                'name' => $project->title,
                'type' => $project->category,
                'summary' => $project->summary,
                'tags' => $project->tags ?? [],
                'theme' => $project->theme,
            ])
            ->all();

        return view('portfolio', [
            'services' => [
                ['number' => '01', 'title' => 'Plan your idea', 'description' => 'We help you decide what to build, who it is for, and what matters most.'],
                ['number' => '02', 'title' => 'Design the experience', 'description' => 'We make every page simple, clear, and easy for your customers to use.'],
                ['number' => '03', 'title' => 'Build it properly', 'description' => 'We turn the approved design into a fast, reliable website or online system.'],
                ['number' => '04', 'title' => 'Support your growth', 'description' => 'After launch, we help you improve, update, and grow your website over time.'],
            ],
            'projects' => $projects ?: [
                [
                    'index' => '01',
                    'name' => 'Pamoja',
                    'type' => 'Online shop concept',
                    'summary' => 'A simple online shop that helps customers find what they need and complete an order without confusion.',
                    'tags' => ['Easy ordering', 'Mobile friendly', 'Simple checkout'],
                    'theme' => 'violet',
                ],
                [
                    'index' => '02',
                    'name' => 'Mwangaza',
                    'type' => 'Business dashboard concept',
                    'summary' => 'One clear place for a busy team to see progress, follow daily work, and make better decisions.',
                    'tags' => ['Clear reports', 'Live updates', 'Simple controls'],
                    'theme' => 'lime',
                ],
                [
                    'index' => '03',
                    'name' => 'Kijiji',
                    'type' => 'Community website concept',
                    'summary' => 'A welcoming website where people can share useful information, events, and opportunities.',
                    'tags' => ['Easy to use', 'Community news', 'Works everywhere'],
                    'theme' => 'orange',
                ],
            ],
            'disciplines' => [
                ['title' => 'Understand', 'items' => ['Your goals', 'Your customers', 'Your priorities']],
                ['title' => 'Create', 'items' => ['Clear pages', 'Simple journeys', 'A strong look']],
                ['title' => 'Deliver', 'items' => ['A working website', 'A smooth launch', 'Ongoing help']],
            ],
            'teamMembers' => TeamMember::active()->limit(6)->get(),
        ]);
    }
}

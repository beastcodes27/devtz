<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\ProjectImage;
use App\Models\TeamMember;
use Illuminate\Contracts\View\View;

class PortfolioController extends Controller
{
    public function __invoke(): View
    {
        $projects = Project::featured()
            ->with('images')
            ->limit(6)
            ->get()
            ->values()
            ->map(fn (Project $project, int $index): array => [
                'index' => str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT),
                'name' => $project->title,
                'type' => $project->category,
                'summary' => $project->summary,
                'url' => $project->project_url,
                'tags' => $project->tags ?? [],
                'theme' => $project->theme,
                'images' => $project->images
                    ->values()
                    ->map(fn (ProjectImage $image, int $imageIndex): array => [
                        'url' => $image->url(),
                        'alt' => $project->title.' project screenshot '.($imageIndex + 1),
                    ])
                    ->all(),
            ])
            ->all();

        return view('portfolio', [
            'services' => [
                ['number' => '01', 'title' => 'Enterprise software solutions', 'description' => 'Custom software that helps your business manage operations and grow.'],
                ['number' => '02', 'title' => 'Web design and development', 'description' => 'Clear, responsive websites and web applications built around your goals.'],
                ['number' => '03', 'title' => 'Mobile app development', 'description' => 'Practical mobile apps that make your services easy to use on the go.'],
                ['number' => '04', 'title' => 'Automation and RPA', 'description' => 'Automate repetitive tasks so your team can focus on more important work.'],
                ['number' => '05', 'title' => 'System maintenance and support', 'description' => 'Ongoing updates, troubleshooting, and support to keep your systems running.'],
                ['number' => '06', 'title' => 'Payment integration', 'description' => 'Connect your website or app to payment services for convenient transactions.'],
                ['number' => '07', 'title' => 'Security and compliance', 'description' => 'Help protect your systems and support your security and compliance needs.'],
                ['number' => '08', 'title' => 'Tech consulting', 'description' => 'Practical advice to help you choose the right technology for your business.'],
            ],
            'projects' => $projects,
            'disciplines' => [
                ['title' => 'Understand', 'items' => ['Your goals', 'Your customers', 'Your priorities']],
                ['title' => 'Create', 'items' => ['Clear pages', 'Simple journeys', 'A strong look']],
                ['title' => 'Deliver', 'items' => ['A working website', 'A smooth launch', 'Ongoing help']],
            ],
            'teamMembers' => TeamMember::active()->limit(6)->get(),
        ]);
    }
}

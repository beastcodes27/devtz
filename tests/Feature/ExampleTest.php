<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    public function test_portfolio_page_is_available(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('content="width=device-width, initial-scale=1"', false)
            ->assertSee('We make')
            ->assertSee('About us')
            ->assertSee('We build software that works well and lasts.')
            ->assertSee('Services')
            ->assertSee('Our work')
            ->assertSee('Our team')
            ->assertSee('Contact');
    }

    public function test_a_valid_project_inquiry_is_stored(): void
    {
        Storage::fake('local');

        $this->post('/inquiries', [
            'name' => 'Asha M.',
            'email' => 'asha@example.com',
            'company' => 'Example Studio',
            'project_type' => 'web-app',
            'message' => 'We need a focused platform for coordinating our field team.',
        ])->assertRedirect(route('portfolio', ['sent' => 1]).'#contact')
            ->assertSessionHas('success');

        $this->assertCount(1, Storage::disk('local')->allFiles('portfolio-inquiries'));
    }

    public function test_an_invalid_project_inquiry_is_rejected(): void
    {
        Storage::fake('local');

        $this->post('/inquiries', [
            'name' => '',
            'email' => 'not-an-email',
            'project_type' => 'unknown',
            'message' => 'Too short',
        ])->assertSessionHasErrors(['name', 'email', 'project_type', 'message']);

        $this->assertCount(0, Storage::disk('local')->allFiles('portfolio-inquiries'));
    }
}

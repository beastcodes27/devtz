<?php

namespace Tests\Feature\Dashboard;

use App\Mail\PortfolioInquiryReply;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class InquiryControllerTest extends TestCase
{
    private const INQUIRY_ID = '123e4567-e89b-42d3-a456-426614174000';

    use RefreshDatabase;

    public function test_guest_cannot_open_client_emails_or_send_replies(): void
    {
        Mail::fake();

        $this->get(route('dashboard.inquiries.index'))
            ->assertRedirect(route('login'));
        $this->get(route('dashboard.inquiries.show', self::INQUIRY_ID))
            ->assertRedirect(route('login'));
        $this->post(route('dashboard.inquiries.replies.store', self::INQUIRY_ID), [
            'subject' => 'Project update',
            'message' => 'This should not be delivered.',
        ])->assertRedirect(route('login'));

        Mail::assertNothingOutgoing();
    }

    public function test_authenticated_user_can_search_and_open_client_emails(): void
    {
        Storage::fake('local');
        $user = User::factory()->create();
        $this->storeInquiry();

        $this->actingAs($user)
            ->get(route('dashboard.inquiries.index'))
            ->assertOk()
            ->assertSee('Client emails')
            ->assertSee('Asha Mwinyi')
            ->assertSee('asha@example.com');
        $this->actingAs($user)
            ->get(route('dashboard.inquiries.index', ['search' => 'No matching client']))
            ->assertOk()
            ->assertDontSee('Asha Mwinyi');
        $this->actingAs($user)
            ->get(route('dashboard.inquiries.show', self::INQUIRY_ID))
            ->assertOk()
            ->assertSee('field operations platform')
            ->assertSee('Send email');
    }

    public function test_authenticated_user_can_reply_to_client_and_reply_is_recorded(): void
    {
        Mail::fake();
        Storage::fake('local');
        $this->freezeTime();
        $user = User::factory()->create([
            'name' => 'DevTZ Admin',
            'email' => 'hello@devtz.com',
        ]);
        $this->storeInquiry();

        $response = $this->actingAs($user)->post(
            route('dashboard.inquiries.replies.store', self::INQUIRY_ID),
            [
                'subject' => 'Your DevTZ project plan',
                'message' => 'Thank you for reaching out. We would be glad to discuss the next steps.',
            ],
        );

        $response->assertRedirect(route('dashboard.inquiries.show', self::INQUIRY_ID))
            ->assertSessionHas('success');
        Mail::assertSent(PortfolioInquiryReply::class, function (PortfolioInquiryReply $mail): bool {
            return $mail->hasTo('asha@example.com')
                && $mail->hasSubject('Your DevTZ project plan')
                && $mail->clientName === 'Asha Mwinyi'
                && str_contains($mail->replyMessage, 'next steps');
        });
        $storedInquiry = json_decode(
            Storage::disk('local')->get('portfolio-inquiries/'.self::INQUIRY_ID.'.json'),
            true,
            flags: JSON_THROW_ON_ERROR,
        );
        $this->assertSame('Your DevTZ project plan', $storedInquiry['replies'][0]['subject']);
        $this->assertSame('hello@devtz.com', $storedInquiry['replies'][0]['sent_by']['email']);
        $this->assertSame(now()->toIso8601String(), $storedInquiry['last_replied_at']);
    }

    public function test_invalid_reply_is_rejected_without_sending_email(): void
    {
        Mail::fake();
        Storage::fake('local');
        $user = User::factory()->create();
        $this->storeInquiry();

        $this->actingAs($user)->post(
            route('dashboard.inquiries.replies.store', self::INQUIRY_ID),
            ['subject' => "Invalid\nsubject", 'message' => ''],
        )->assertSessionHasErrors(['subject', 'message']);

        Mail::assertNothingOutgoing();
        $storedInquiry = json_decode(
            Storage::disk('local')->get('portfolio-inquiries/'.self::INQUIRY_ID.'.json'),
            true,
            flags: JSON_THROW_ON_ERROR,
        );
        $this->assertArrayNotHasKey('replies', $storedInquiry);
    }

    public function test_unknown_or_invalid_inquiry_identifier_returns_not_found(): void
    {
        Storage::fake('local');
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('dashboard.inquiries.show', '223e4567-e89b-42d3-a456-426614174000'))
            ->assertNotFound();
        $this->actingAs($user)
            ->get('/dashboard/inquiries/not-a-uuid')
            ->assertNotFound();
    }

    public function test_client_email_pages_escape_inquiry_and_reply_content(): void
    {
        Storage::fake('local');
        $user = User::factory()->create();
        $this->storeInquiry([
            'name' => '<script>alert("name")</script>',
            'message' => '<img src=x onerror=alert("message")>',
            'replies' => [[
                'id' => 'reply-1',
                'subject' => '<script>alert("subject")</script>',
                'message' => '<img src=x onerror=alert("reply")>',
                'sent_at' => '2026-10-28T10:30:00+03:00',
                'sent_by' => ['name' => 'Admin', 'email' => 'hello@devtz.com'],
            ]],
        ]);

        $response = $this->actingAs($user)
            ->get(route('dashboard.inquiries.show', self::INQUIRY_ID));

        $response->assertOk()
            ->assertSee('&lt;script&gt;', false)
            ->assertSee('&lt;img', false)
            ->assertDontSee('<script>alert', false)
            ->assertDontSee('<img src=x', false);
    }

    /** @param array<string, mixed> $overrides */
    private function storeInquiry(array $overrides = []): void
    {
        $inquiry = array_merge([
            'id' => self::INQUIRY_ID,
            'name' => 'Asha Mwinyi',
            'email' => 'asha@example.com',
            'company' => 'Asha Logistics',
            'project_type' => 'web-app',
            'message' => 'We need a field operations platform for our growing delivery team.',
            'submitted_at' => '2026-10-28T09:00:00+03:00',
        ], $overrides);

        Storage::disk('local')->put(
            'portfolio-inquiries/'.self::INQUIRY_ID.'.json',
            json_encode($inquiry, JSON_PRETTY_PRINT | JSON_THROW_ON_ERROR),
        );
    }
}

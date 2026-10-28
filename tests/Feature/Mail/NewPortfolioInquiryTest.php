<?php

namespace Tests\Feature\Mail;

use App\Mail\NewPortfolioInquiry;
use Tests\TestCase;

class NewPortfolioInquiryTest extends TestCase
{
    public function test_email_contains_inquiry_details_and_escapes_user_content(): void
    {
        $mailable = new NewPortfolioInquiry([
            'id' => 'inquiry-123',
            'name' => '<script>alert("name")</script>',
            'email' => 'asha@example.com',
            'company' => '<img src=x onerror=alert("company")>',
            'project_type' => 'web-app',
            'message' => '<script>alert("message")</script> Build a field operations platform.',
            'submitted_at' => '2026-10-28T12:00:00+03:00',
        ]);

        $html = $mailable->render();

        $mailable->assertHasSubject('New project inquiry from DevTZ.com');
        $mailable->assertHasReplyTo('asha@example.com');
        $this->assertStringContainsString('Build a field operations platform.', $html);
        $this->assertStringContainsString('&lt;script&gt;', $html);
        $this->assertStringContainsString('&lt;img', $html);
        $this->assertStringNotContainsString('<script>alert', $html);
        $this->assertStringNotContainsString('<img src=x', $html);
    }
}

<?php

namespace Tests\Feature\Mail;

use App\Mail\PortfolioInquiryReply;
use Tests\TestCase;

class PortfolioInquiryReplyTest extends TestCase
{
    public function test_reply_email_contains_message_and_escapes_client_content(): void
    {
        $mailable = new PortfolioInquiryReply(
            clientName: '<script>alert("client")</script>',
            replySubject: 'Your DevTZ project plan',
            replyMessage: '<img src=x onerror=alert("message")> Here are the next steps.',
        );

        $html = $mailable->render();

        $mailable->assertHasSubject('Your DevTZ project plan');
        $this->assertStringContainsString('Here are the next steps.', $html);
        $this->assertStringContainsString('&lt;script&gt;', $html);
        $this->assertStringContainsString('&lt;img', $html);
        $this->assertStringNotContainsString('<script>alert', $html);
        $this->assertStringNotContainsString('<img src=x', $html);
    }
}

<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\SendInquiryReplyRequest;
use App\Mail\PortfolioInquiryReply;
use App\PortfolioInquiryStore;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class InquiryReplyController extends Controller
{
    public function __invoke(
        SendInquiryReplyRequest $request,
        string $inquiry,
        PortfolioInquiryStore $inquiryStore,
    ): RedirectResponse {
        $inquiryData = $inquiryStore->find($inquiry);

        abort_if($inquiryData === null, 404);

        $reply = $request->validated();

        Mail::to($inquiryData['email'])->send(new PortfolioInquiryReply(
            clientName: $inquiryData['name'],
            replySubject: $reply['subject'],
            replyMessage: $reply['message'],
        ));

        $inquiryStore->addReply($inquiry, [
            'id' => (string) Str::uuid(),
            'subject' => $reply['subject'],
            'message' => $reply['message'],
            'sent_at' => now()->toIso8601String(),
            'sent_by' => [
                'name' => $request->user()->name,
                'email' => $request->user()->email,
            ],
        ]);

        return to_route('dashboard.inquiries.show', $inquiry)
            ->with('success', 'Reply sent to '.$inquiryData['email'].'.');
    }
}

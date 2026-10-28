<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreInquiryRequest;
use App\Mail\NewPortfolioInquiry;
use App\PortfolioInquiryStore;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class InquiryController extends Controller
{
    public function __invoke(StoreInquiryRequest $request, PortfolioInquiryStore $inquiryStore): RedirectResponse
    {
        $inquiry = array_merge($request->validated(), [
            'id' => (string) Str::uuid(),
            'submitted_at' => now()->toIso8601String(),
        ]);

        $inquiryStore->store($inquiry);

        Mail::to(config('mail.contact.address'))->send(new NewPortfolioInquiry($inquiry));

        return to_route('portfolio', ['sent' => 1])
            ->withFragment('contact')
            ->with('success', 'Thanks — your project brief has been sent.');
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreInquiryRequest;
use App\Mail\NewPortfolioInquiry;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class InquiryController extends Controller
{
    public function __invoke(StoreInquiryRequest $request): RedirectResponse
    {
        $inquiry = array_merge($request->validated(), [
            'id' => (string) Str::uuid(),
            'submitted_at' => now()->toIso8601String(),
        ]);

        Storage::disk('local')->put(
            'portfolio-inquiries/'.$inquiry['id'].'.json',
            json_encode($inquiry, JSON_PRETTY_PRINT | JSON_THROW_ON_ERROR),
        );

        Mail::to(config('mail.contact.address'))->send(new NewPortfolioInquiry($inquiry));

        return to_route('portfolio', ['sent' => 1])
            ->withFragment('contact')
            ->with('success', 'Thanks — your project brief has been sent.');
    }
}

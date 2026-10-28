<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\PortfolioInquiryStore;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class InquiryController extends Controller
{
    public function index(Request $request, PortfolioInquiryStore $inquiryStore): View
    {
        $search = $request->string('search')->trim();
        $inquiries = $inquiryStore->all();

        if ($search->isNotEmpty()) {
            $needle = $search->lower()->toString();
            $inquiries = $inquiries->filter(function (array $inquiry) use ($needle): bool {
                $haystack = implode(' ', [
                    $inquiry['name'] ?? '',
                    $inquiry['email'] ?? '',
                    $inquiry['company'] ?? '',
                    $inquiry['project_type'] ?? '',
                    $inquiry['message'] ?? '',
                ]);

                return Str::contains(Str::lower($haystack), $needle);
            })->values();
        }

        return view('dashboard.inquiries.index', [
            'inquiries' => $inquiries,
            'search' => $search->toString(),
        ]);
    }

    public function show(string $inquiry, PortfolioInquiryStore $inquiryStore): View
    {
        $inquiryData = $inquiryStore->find($inquiry);

        abort_if($inquiryData === null, 404);

        return view('dashboard.inquiries.show', [
            'inquiry' => $inquiryData,
        ]);
    }
}

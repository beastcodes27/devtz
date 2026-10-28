<x-mail::message>
# Hello {{ $clientName }},

{{ $replyMessage }}

If you have any questions, reply to this email and the DevTZ team will get back to you.

Thanks,<br>
The {{ config('app.name') }} team
</x-mail::message>

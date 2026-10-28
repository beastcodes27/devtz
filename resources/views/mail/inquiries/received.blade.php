<x-mail::message>
# New project inquiry

A new inquiry was submitted through the DevTZ portfolio contact form.

<x-mail::panel>
**Name:** {{ $inquiry['name'] }}  
**Email:** {{ $inquiry['email'] }}  
**Company:** {{ $inquiry['company'] ?: 'Not provided' }}  
**Project type:** {{ str($inquiry['project_type'])->replace('-', ' ')->title() }}  
**Submitted:** {{ $inquiry['submitted_at'] }}
</x-mail::panel>

## Project brief

{{ $inquiry['message'] }}

You can reply directly to this email to contact {{ $inquiry['name'] }}.

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>

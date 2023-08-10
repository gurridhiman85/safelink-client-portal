<x-mail::message>
{{-- Greeting --}}
@if (! empty($greeting))
# {{ $greeting }}
@else
@if ($level === 'error')
# @lang('Whoops!')
@else
# @lang('Hello!') {{ $name }}, <p>Welcome to Safelink! We're thrilled to have you on board. To start using our platform, please activate your account by clicking the button below:</p>
@endif
@endif
{{-- Action Button --}}
@isset($actionText)
<?php
    $color = match ($level) {
        'success', 'error' => $level,
        default => 'primary',
    };
?>
<x-mail::button :url="$actionUrl" :color="$color">
{{ $actionText }}
</x-mail::button>
@endisset
@isset($actionText)
<p>If the button doesn't work, you can copy and paste the following link into your browser's address bar:</p><br> {{ $actionUrl }} <br><br>
@endisset
<p>Thank you for joining Safelink! If you have any questions or need assistance, don't hesitate to reach out to our support team at support@safe-link.net</p>
<br>
Best Regards,<br>
Safelink Team
</x-mail::message>
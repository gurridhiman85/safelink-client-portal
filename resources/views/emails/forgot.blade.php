<x-mail::message>
{{-- Greeting --}}
@if (! empty($greeting))
# {{ $greeting }}
@else
@if ($level === 'error')
# @lang('Whoops!')
@else
# @lang('Hello!') {{ $name }}, <p>We received a request to reset your Safelink account password. To set a new password, click the button below:</p>
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
<p>If you didn't request a password reset, please ignore this email. Your account is safe and secure.</p>
<br>
Best Regards,<br>
Safelink Team
</x-mail::message>
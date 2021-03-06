@component('mail::message')
    {{-- Greeting --}}
    @if (! empty($greeting))
        # {{ $greeting }}
    @else
        @if ($level == 'error')
            # Whoops!
        @endif
    @endif

    {{-- Intro Lines --}}
    @foreach ($introLines as $line)
        {!! $line !!}

    @endforeach

    {{-- Action Button --}}
    @if (isset($actionText))
        <?php
        switch ($level) {
            case 'success':
                $color = 'green';
                break;
            case 'error':
                $color = 'red';
                break;
            default:
                $color = 'blue';
        }
        ?>
        @component('mail::button', ['url' => $actionUrl, 'color' => $color])
            {!! $actionText !!}
        @endcomponent
    @endif

    {{-- Outro Lines --}}
    @foreach ($outroLines as $line)
        {!! $line !!}

    @endforeach

    @if (! empty($salutation))
        {{ $salutation }}
    @endif

@endcomponent

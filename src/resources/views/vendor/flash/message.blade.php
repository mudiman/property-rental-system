@foreach ((array) session('flash_notification') as $message)
    @if (isset($message['overlay']) && $message['overlay'])
        @include('flash::modal', [
            'modalClass' => 'flash-modal',
            'title'      => $message['title'],
            'body'       => $message['message']
        ])
    @else
        <div class="alert
                    alert-{{ isset($message['important']) ? $message['level']: '' }}
                    {{ isset($message['important']) ? 'alert-important' : '' }}"
        >
            @if (isset($message['important']) && $message['important'])
                <button type="button"
                        class="close"
                        data-dismiss="alert"
                        aria-hidden="true"
                >&times;</button>
            @endif

            {!! isset($message['important']) ? $message['message']: '' !!}
        </div>
    @endif
@endforeach

{{ session()->forget('flash_notification') }}

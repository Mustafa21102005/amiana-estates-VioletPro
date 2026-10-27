@props(['text'])

@foreach (preg_split('/\R{2,}/', trim((string) $text)) as $paragraph)
    @if (trim($paragraph) !== '')
        <p {{ $attributes }}>{!! nl2br(e(trim($paragraph))) !!}</p>
    @endif
@endforeach

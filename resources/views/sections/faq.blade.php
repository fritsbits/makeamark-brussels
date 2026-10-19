<section id="faq" class="faq container">
    <flux:heading level="2" class="h-display faq__heading">{{ $site['faq']['heading'] }}</flux:heading>
    <div class="faq__list">
        @foreach ($faq as $item)
            <details class="faq__item">
                <summary class="faq__summary">
                    <span class="h-display h-display--light faq__num" aria-hidden="true">{{ sprintf('%02d', $loop->iteration) }}</span>
                    <span class="faq__question">{{ $item['question'] }}</span>
                    <span class="faq__icon" aria-hidden="true"></span>
                </summary>
                <div class="faq__answer">{!! $item['html'] !!}</div>
            </details>
        @endforeach
    </div>
</section>

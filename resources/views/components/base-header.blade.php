@props(['title', 'icon' => 'fa-solid fa-book'])

<div {{ $attributes->merge(['class' => 'card-header py-3 d-flex flex-row align-items-center justify-content-between']) }}>
    <h3 class="m-0 font-weight-bold">
        <i class="{{ $icon }} pr-2"></i> {{ $title }}
    </h3>
    <div class="header-actions d-flex gap-2">
        {{ $slot }}
    </div>
</div>

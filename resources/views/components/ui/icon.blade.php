@props([
    'name',
    'class' => 'h-4 w-4',
])

@switch($name)
    @case('plus')
        <svg {{ $attributes->merge(['class' => $class]) }} viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14M5 12h14" />
        </svg>
        @break

    @case('arrow-left')
        <svg {{ $attributes->merge(['class' => $class]) }} viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
        </svg>
        @break

    @case('pencil')
        <svg {{ $attributes->merge(['class' => $class]) }} viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 2.651 2.651a1.875 1.875 0 0 1 0 2.652L9.75 19.553 4.5 21l1.447-5.25 9.763-9.763a1.875 1.875 0 0 1 2.652 0Z" />
        </svg>
        @break

    @case('trash')
        <svg {{ $attributes->merge(['class' => $class]) }} viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 7.5h15m-12 0V6a1.5 1.5 0 0 1 1.5-1.5h6A1.5 1.5 0 0 1 16.5 6v1.5m-9 0v10.125A2.625 2.625 0 0 0 10.125 20.25h3.75A2.625 2.625 0 0 0 16.5 17.625V7.5" />
        </svg>
        @break

    @case('cart')
        <svg {{ $attributes->merge(['class' => $class]) }} viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386a1.5 1.5 0 0 1 1.464 1.174L5.5 6h14.318a.75.75 0 0 1 .728.932l-1.5 6A.75.75 0 0 1 18.318 13.5H7.25M7.25 13.5 5.5 6m1.75 7.5L6 16.5a1.5 1.5 0 0 0 1.37 2.13h10.88M9 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm9 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
        </svg>
        @break

    @case('refresh')
        <svg {{ $attributes->merge(['class' => $class]) }} viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992V4.356m-1.5 11.148A8.25 8.25 0 1 1 6.982 6.982L9.75 9.75" />
        </svg>
        @break

    @case('checkout')
        <svg {{ $attributes->merge(['class' => $class]) }} viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 8.25V6.75A4.5 4.5 0 0 0 12 2.25a4.5 4.5 0 0 0-4.5 4.5v1.5M6 9.75h12l-.82 8.198a1.5 1.5 0 0 1-1.493 1.352H8.313A1.5 1.5 0 0 1 6.82 17.948L6 9.75Z" />
        </svg>
        @break

    @case('admin')
        <svg {{ $attributes->merge(['class' => $class]) }} viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m6 2.25c0 4.97-4.03 9-9 9s-9-4.03-9-9 4.03-9 9-9 9 4.03 9 9Z" />
        </svg>
        @break

    @default
        <svg {{ $attributes->merge(['class' => $class]) }} viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
            <circle cx="12" cy="12" r="9" />
        </svg>
@endswitch

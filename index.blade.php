<x-app-layout>
    <x-slot name="header">
        {{ __('not-received.title') }}
    </x-slot>

    <x-slot name="panel">
        <form method="GET" action="{{ route('not-received') }}">
            @include('ttn.filters', [
                'pagedOutput' => $pagedOutput,
                'filters' => $filters,
                'showStatus' => false
            ])
        </form>
    </x-slot>

    <div>
        @include('ttn.ttn-list', [
            'ttns' => $ttnList,
        ])
    </div>

    <x-slot name="sum"></x-slot>
</x-app-layout>


<x-filament-widgets::widget>
    <x-filament::section>

        <div>

            <div> @livewire('restock-products')</div>
            <br>
            <div>
                <x-filament::button wire:click="placeSaleOrder">
                    {{ __('Place Sale Order') }}
                </x-filament::button>
            </div>

        </div>

    </x-filament::section>
</x-filament-widgets::widget>

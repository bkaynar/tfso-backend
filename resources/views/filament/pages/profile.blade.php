<x-filament::page>
    <form wire:submit.prevent="save">
        {{ $this->form }}
        <div class="col-span-2 mt-6">
            <x-filament::button type="submit">
                Kaydet
            </x-filament::button>
        </div>
    </form>
</x-filament::page>

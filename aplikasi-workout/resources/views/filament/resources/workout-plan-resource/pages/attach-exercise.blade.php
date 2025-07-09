<x-filament-panels::page>
    <form wire:submit="attach">
        {{ $this->form }}

        <x-filament-panels::form.actions 
            :actions="$this->getFormActions()"
        />
    </form>
</x-filament-panels::page>
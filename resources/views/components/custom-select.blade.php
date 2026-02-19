@props([
    'name' => 'select',
    'options' => [],
    'placeholder' => 'Select option',
    'selected' => null,
    'value' => null,
    'class' => '',
    'style' => '',
    'wireModel' => null,
    'id' => null,
])

@php
    $selectedValue = $value ?? ($selected ?? old($name));
    $selectedValue = $selectedValue !== null && $selectedValue !== '' ? $selectedValue : null;
    $selectedLabel = null;

    // Check if we're in a Livewire component context
$isLivewire = isset($this) && $this instanceof \Livewire\Component;

// Normalize options first
$normalizedOptions = collect($options)
    ->map(function ($option) {
        if (is_array($option)) {
            return [
                'value' => $option['value'] ?? ($option['id'] ?? null),
                'label' => $option['label'] ?? ($option['name'] ?? ($option['text'] ?? '')),
            ];
        }
        return ['value' => $option, 'label' => $option];
    })
    ->toArray();

// Find the label for the selected value
if ($selectedValue !== null) {
    foreach ($normalizedOptions as $option) {
        if ($option['value'] == $selectedValue) {
            $selectedLabel = $option['label'];
            break;
        }
    }
}
@endphp

<div class="custom-select-wrapper {{ $class }}" style="{{ $style }}" x-data="{
    open: false,
    selected: @js($selectedValue && $selectedLabel ? ['value' => $selectedValue, 'label' => $selectedLabel] : null),
    options: @js($normalizedOptions),
    placeholder: @js($placeholder),
    selectOption(option) {
        this.selected = option;
        this.open = false;
        $dispatch('select-changed', { name: '{{ $name }}', value: option.value, label: option.label });
        @if ($wireModel && $isLivewire) @this.set('{{ $wireModel }}', option.value); @endif
    }
}"
    @click.outside="open = false" @if ($id) id="{{ $id }}" @endif>
    <div @click="open = !open" class="custom-select-input" :class="{ 'open': open }">
        <span class="custom-select-text" :class="{ 'placeholder': !selected || selected.value === '' || selected.value === null }"
            x-text="(selected && (selected.value !== '' && selected.value !== null)) ? selected.label : placeholder"></span>
        <svg class="custom-select-arrow" :class="{ 'open': open }" fill="none" stroke="currentColor"
            viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
        </svg>
    </div>

    <div x-show="open" x-transition class="custom-select-dropdown" style="display: none;">
        <template x-for="option in options" :key="option.value">
            <div @click="selectOption(option)" class="custom-select-option"
                :class="{ 'selected': selected && selected.value === option.value }">
                <span class="custom-select-option-label" x-text="option.label"></span>
                <svg x-show="selected && selected.value === option.value" class="custom-select-checkmark" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24" style="display: none;">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
        </template>
    </div>

    <input type="hidden" name="{{ $name }}" :value="selected && selected.value ? selected.value : ''" />
</div>

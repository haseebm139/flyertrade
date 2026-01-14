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
    // Check if we're in a Livewire component context
$isLivewire = isset($this) && $this instanceof \Livewire\Component;

// Get initial value - prioritize Livewire property if wireModel is set
$initialValue = null;
if ($wireModel && $isLivewire) {
    try {
        $wireValue = data_get($this, $wireModel);
        if ($wireValue !== null && $wireValue !== '') {
            $initialValue = $wireValue;
        }
    } catch (\Exception $e) {
        // Fallback to other sources
    }
}

// If no value from Livewire, use other sources
if ($initialValue === null) {
    $initialValue = $value ?? ($selected ?? old($name));
}

$initialValue = $initialValue !== null && $initialValue !== '' ? $initialValue : null;
$initialLabel = null;

// Normalize options
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

// Find label for initial value
if ($initialValue !== null) {
    foreach ($normalizedOptions as $option) {
        if (
            $option['value'] !== '' &&
            $option['value'] !== null &&
            (string) $option['value'] === (string) $initialValue
        ) {
            $initialLabel = $option['label'];
                break;
            }
        }
    }
@endphp

<div class="custom-select-wrapper {{ $class }}" style="{{ $style }}" x-data="{
    open: false,
    selected: @js($initialValue && $initialLabel ? ['value' => $initialValue, 'label' => $initialLabel] : null),
    options: @js($normalizedOptions),
    placeholder: @js($placeholder),
    @if ($wireModel && $isLivewire) wireModel: '{{ $wireModel }}',
        init() {
            // Watch for Livewire property changes
            this.$watch(`$wire.${this.wireModel}`, (newValue) => {
                this.syncWithLivewire(newValue);
            });
            
            // Initial sync with Livewire value
            const currentValue = this.$wire.get(this.wireModel);
            if (currentValue !== null && currentValue !== '' && currentValue !== undefined) {
                this.syncWithLivewire(currentValue);
            }
        },
        syncWithLivewire(value) {
            if (value !== null && value !== '' && value !== undefined) {
                const matchingOption = this.options.find(opt => 
                    String(opt.value) === String(value)
                );
                if (matchingOption) {
                    this.selected = matchingOption;
                } else {
                    this.selected = null;
                }
            } else {
                this.selected = null;
            }
        }, @endif
    selectOption(option) {
        this.selected = option;
        this.open = false;
        $dispatch('select-changed', { name: '{{ $name }}', value: option.value, label: option.label });
        @if ($wireModel && $isLivewire) // Update Livewire property
            this.$wire.set(this.wireModel, option.value); @endif
    }
}"
    @click.outside="open = false" @if ($id) id="{{ $id }}" @endif>

    <div @click="open = !open" class="custom-select-input" :class="{ 'open': open }">
        <span class="custom-select-text" :class="{ 'placeholder': !selected || !selected.value }"
            x-text="selected && selected.value ? selected.label : placeholder"></span>
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

<style>
    .custom-select-wrapper {
        position: relative;
        border: 1px solid #AAAAAA;
        width: 100%;
    }

    .custom-select-input {
        width: 100%;
        background: white;
        border-radius: 10px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: space-between;
        transition: border-color 0.2s;
    }

    .custom-select-input:hover {
        border-color: #ddd;
    }

    .custom-select-input.open {
        border-color: #ddd;
    }

    .custom-select-text {
        font-size: 0.833vw;
        color: #1b1b1b !important;
        flex: 1;
    }

    .custom-select-text.placeholder {
        color: #AAAAAA !important;
        background-color: rgba(0, 0, 0, 0) !important;
        opacity: 1 !important;
    }

    .placeholder {
        opacity: 1 !important;
    }

    .custom-select-arrow {
        width: 0.729vw;
        height: 0.729vw;
        color: #AAAAAA;
        transition: transform 0.2s;
        flex-shrink: 0;
    }

    .custom-select-arrow.open {
        transform: rotate(180deg);
    }

    .custom-select-dropdown {
        position: absolute;
        z-index: 50;
        width: 100%;
        margin-top: 0.417vw;
        background: white;
        border-radius: 0.417vw;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        border: 1px solid #e5e7eb;
        overflow: hidden;
        max-height: 10.417vw;
        overflow-y: auto;
        left: 0px;
    }

    .custom-select-option {
        padding: 0.625vw 0.833vw;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: space-between;
        transition: background-color 0.2s;
        border-bottom: 1px solid #f3f4f6;
        font-weight: 500;
    }

    .custom-select-option:last-child {
        border-bottom: none;
    }

    .custom-select-option:hover {
        background-color: rgba(0, 78, 66, 0.1)
    }

    .custom-select-option.selected {
        background-color: rgba(0, 78, 66, 0.1)
    }

    .custom-select-option-label {
        font-size: 0.833vw;
        color: #374151;
    }

    .custom-select-option.selected .custom-select-option-label {
        color: #111827;
        font-weight: 500;
    }

    .custom-select-checkmark {
        width: 0.833vw;
        height: 0.833vw;
        color: #0d9488;
        flex-shrink: 0;
    }
</style>

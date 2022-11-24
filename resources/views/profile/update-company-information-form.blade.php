<x-jet-form-section submit="updateCompanyInformation">
  <x-slot name="title">
    {{ __('Company Information') }}
  </x-slot>

  <x-slot name="description">
    {{ __('Update your account\'s company information and contact details.') }}
  </x-slot>

  <x-slot name="form">

    <x-jet-action-message on="saved">
      {{ __('Saved.') }}
    </x-jet-action-message>

    <!-- Name -->
    <div class="mb-1">
      <x-jet-label class="form-label" for="company_name" value="{{ __('Company Name') }}" />
      <x-jet-input id="company_name" type="text" class="{{ $errors->has('company_name') ? 'is-invalid' : '' }}"
        wire:model.defer="state.company_name" autocomplete="company_name" />
      <x-jet-input-error for="company_name" />
    </div>

    <div class="mb-1">
      <x-jet-label class="form-label" for="website" value="{{ __('Website') }}" />
      <x-jet-input id="website" type="text" class="{{ $errors->has('website') ? 'is-invalid' : '' }}"
        wire:model.defer="state.website" autocomplete="website" />
      <x-jet-input-error for="website" />
    </div>

    <div class="mb-1">
      <x-jet-label class="form-label" for="contact_number" value="{{ __('Contact Number') }}" />
      <x-jet-input id="contact_number" type="text" class="{{ $errors->has('contact_number') ? 'is-invalid' : '' }}"
        wire:model.defer="state.contact_number" autocomplete="contact_number" />
      <x-jet-input-error for="contact_number" />
    </div>

    <div class="mb-1">
      <x-jet-label class="form-label" for="address" value="{{ __('Address') }}" />
      <x-jet-input id="address" type="text" class="{{ $errors->has('address') ? 'is-invalid' : '' }}"
        wire:model.defer="state.address" autocomplete="address" />
      <x-jet-input-error for="address" />
    </div>
  </x-slot>

  <x-slot name="actions">
    <div class="d-flex align-items-baseline">
      <x-jet-button>
        {{ __('Save') }}
      </x-jet-button>
    </div>
  </x-slot>
</x-jet-form-section>

<x-jet-form-section submit="updateProfileInformation">
  <x-slot name="title">
    {{ __('Profile Information') }}
  </x-slot>

  <x-slot name="description">
    {{ __('Update your account\'s profile information and email address.') }}
  </x-slot>

  <x-slot name="form">

    <x-jet-action-message on="saved">
      {{ __('Saved.') }}
    </x-jet-action-message>

    <!-- Profile Photo -->
    @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
      <div class="mb-1" x-data="{photoName: null, photoPreview: null}">
        <!-- Profile Photo File Input -->
        <input type="file" hidden wire:model="photo" x-ref="photo"
          x-on:change=" photoName = $refs.photo.files[0].name; const reader = new FileReader(); reader.onload = (e) => { photoPreview = e.target.result;}; reader.readAsDataURL($refs.photo.files[0]);" />

        <!-- Current Profile Photo -->
        <div class="mt-2" x-show="! photoPreview">
          <img src="{{ $this->user->profile_photo_url }}" class="rounded-circle" height="80px" width="80px">
        </div>

        <!-- New Profile Photo Preview -->
        <div class="mt-2" x-show="photoPreview">
          <img x-bind:src="photoPreview" class="rounded-circle" width="80px" height="80px">
        </div>

        <x-jet-secondary-button class="mt-2 me-2" type="button" x-on:click.prevent="$refs.photo.click()">
          {{ __('Select A New Photo') }}
        </x-jet-secondary-button>

        @if ($this->user->profile_photo_path)
          <button type="button" class="btn btn-danger text-uppercase mt-2" wire:click="deleteProfilePhoto">
            {{ __('Remove Photo') }}
          </button>
        @endif
        <x-jet-input-error for="photo" class="mt-2" />
      </div>
    @endif


    @if(request()->user()->hasRole('Supplier') || request()->user()->hasRole('Client'))
  <!--     <div class="mb-1">
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
      </div> -->
    @endif

    <!-- Name -->
    <div class="mb-1">
      <x-jet-label class="form-label" for="first_name" value="{{ __('First Name') }}" />
      <x-jet-input id="first_name" type="text" class="{{ $errors->has('first_name') ? 'is-invalid' : '' }}"
        wire:model.defer="state.first_name" autocomplete="first_name" />
      <x-jet-input-error for="first_name" />
    </div>

    <!-- Name -->
    <div class="mb-1">
      <x-jet-label class="form-label" for="last_name" value="{{ __('Last Name') }}" />
      <x-jet-input id="last_name" type="text" class="{{ $errors->has('last_name') ? 'is-invalid' : '' }}"
        wire:model.defer="state.last_name" autocomplete="last_name" />
      <x-jet-input-error for="last_name" />
    </div>

    <!-- Email -->
    <div class="mb-1">
      <x-jet-label class="form-label" for="email" value="{{ __('Email') }}" />
      <x-jet-input id="email" type="email" class="{{ $errors->has('email') ? 'is-invalid' : '' }}"
        wire:model.defer="state.email" />
      <x-jet-input-error for="email" />
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

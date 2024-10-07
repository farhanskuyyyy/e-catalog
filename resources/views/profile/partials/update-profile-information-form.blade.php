<section>
    <header>
        <h2 class="text-lg font-medium ">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 ">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6" enctype="multipart/form-data">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" name="name" type="text" class="mt-1 form-control " :value="old('name', $user->name)"
                required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <div class="my-3" id="preview-image">
                <img src="{{ Storage::url(Auth::user()->avatar) }}" alt="{{ Auth::user()->name }}" class="rounded mt-2"
                    style="width: 90px;height:90px">
            </div>
            <br>
            <x-input-label for="avatar" :value="__('Avatar')" />
            <br>
            <x-text-input id="avatar" class="block form-control " type="file" name="avatar" autofocus
                autocomplete="avatar" />
            <x-input-error :messages="$errors->get('avatar')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" class="mt-2 " />
            <x-text-input id="email" name="email" type="email" class="form-control  mb-2" :value="old('email', $user->email)"
                required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !$user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800 dark:text-gray-200">
                        {{ __('Your email address is unverified.') }}

                        <button form="send-verification"
                            class="underline text-sm hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600 dark:text-green-400">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)" class="text-sm">
                    {{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>

@push('scripts')
    <script>
        $('#avatar').change(function() {
            var fileTypes = ['jpg', 'jpeg', 'png', 'gif', 'svg']; //acceptable file types

            var extension = this.files[0].name.split('.').pop().toLowerCase() //file extension from input file
            var isSuccess = fileTypes.indexOf(extension) > -1; //is extension in acceptable types

            if (isSuccess) { //yes
                let reader = new FileReader();
                reader.onload = (e) => {
                    $('#preview-image').html(
                        `<img src="${e.target.result}" alt="Avatar" style="width: 90px;height:90px">`
                    );
                }
                reader.readAsDataURL(this.files[0]);
            } else {
                $('#preview-image').html(this.files[0].name);
            }
        });
    </script>
@endpush

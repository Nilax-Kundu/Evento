<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create New Event') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-8 text-gray-900">
                    <form action="{{ route('organizer.events.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf

                        <!-- Title -->
                        <div>
                            <x-input-label for="title" :value="__('Event Title')" />
                            <x-text-input id="title" class="block mt-1 w-full" type="text" name="title" :value="old('title')" required autofocus />
                            <x-input-error :messages="$errors->get('title')" class="mt-2" />
                        </div>

                        <!-- Description -->
                        <div>
                            <x-input-label for="description" :value="__('Description')" />
                            <textarea id="description" name="description" rows="4" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>{{ old('description') }}</textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Date -->
                            <div>
                                <x-input-label for="date" :value="__('Event Date')" />
                                <x-text-input id="date" class="block mt-1 w-full" type="date" name="date" :value="old('date')" required />
                                <x-input-error :messages="$errors->get('date')" class="mt-2" />
                            </div>

                            <!-- Time -->
                            <div>
                                <x-input-label for="time" :value="__('Event Time')" />
                                <x-text-input id="time" class="block mt-1 w-full" type="time" name="time" :value="old('time')" required />
                                <x-input-error :messages="$errors->get('time')" class="mt-2" />
                            </div>
                        </div>

                        <!-- Location -->
                        <div>
                            <x-input-label for="location" :value="__('Location (Venue or Online Link)')" />
                            <x-text-input id="location" class="block mt-1 w-full" type="text" name="location" :value="old('location')" required />
                            <x-input-error :messages="$errors->get('location')" class="mt-2" />
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Registration Start -->
                            <div>
                                <x-input-label for="registration_start" :value="__('Registration Start (Date & Time)')" />
                                <x-text-input id="registration_start" class="block mt-1 w-full" type="datetime-local" name="registration_start" :value="old('registration_start')" required />
                                <x-input-error :messages="$errors->get('registration_start')" class="mt-2" />
                            </div>

                            <!-- Registration End -->
                            <div>
                                <x-input-label for="registration_end" :value="__('Registration End (Date & Time)')" />
                                <x-text-input id="registration_end" class="block mt-1 w-full" type="datetime-local" name="registration_end" :value="old('registration_end')" required />
                                <x-input-error :messages="$errors->get('registration_end')" class="mt-2" />
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Max Participants -->
                            <div>
                                <x-input-label for="max_participants" :value="__('Maximum Capacity')" />
                                <x-text-input id="max_participants" class="block mt-1 w-full" type="number" min="1" name="max_participants" :value="old('max_participants')" required />
                                <x-input-error :messages="$errors->get('max_participants')" class="mt-2" />
                            </div>

                            <!-- Fee -->
                            <div>
                                <x-input-label for="registration_fee" :value="__('Registration Fee ($)')" />
                                <x-text-input id="registration_fee" class="block mt-1 w-full" type="number" step="0.01" min="0" name="registration_fee" :value="old('registration_fee')" placeholder="Leave empty for free" />
                                <x-input-error :messages="$errors->get('registration_fee')" class="mt-2" />
                            </div>
                        </div>

                        <!-- Image -->
                        <div>
                            <x-input-label for="image" :value="__('Event Image (Optional)')" />
                            <input id="image" class="block mt-1 w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" type="file" name="image" accept="image/*" />
                            <p class="mt-1 text-sm text-gray-500">Max size: 2MB. Format: JPG, PNG</p>
                            <x-input-error :messages="$errors->get('image')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-4 pt-4 border-t border-gray-100">
                            <a href="{{ route('organizer.dashboard') }}" class="text-sm text-gray-600 hover:text-gray-900 mr-4">Cancel</a>
                            <x-primary-button>
                                {{ __('Submit for Approval') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

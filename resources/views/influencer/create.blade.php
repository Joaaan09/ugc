<x-layouts.app :title="__('Influencer')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <div
            class="relative h-full flex-1 overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 p-6">
            <h2 class="text-xl font-semibold text-neutral-800 dark:text-white mb-4">{{__('Create influencer')}}</h2>
            <div class="overflow-x-auto">
                <form action="{{ route('influencer.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-4">
                        <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{__('Name')}}</label>
                        <input type="text" name="name" id="name" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-neutral-800 dark:text-white" placeholder="{{__('Enter influencer name')}}">
                    </div>
                    <div class="mb-4">
                        <label for="profile_picture" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{__('Profile Picture')}}</label>
                        <input type="file" name="profile_picture" id="profile_picture" accept="image/*" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-neutral-800 dark:text-white">
                    </div>

                    <h3 class="text-lg font-medium text-neutral-800 dark:text-white mb-3 mt-6">{{__('Social Profiles')}}</h3>
                    <div class="mb-4 p-4 border border-gray-200 dark:border-neutral-700 rounded-md">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="social_username" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{__('Username')}}</label>
                                <input type="text" name="social_username" id="social_username" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-neutral-800 dark:text-white" placeholder="{{__('Enter social media username')}}">
                            </div>
                            <div>
                                <label for="platform_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{__('Platform')}}</label>
                                <select name="platform_id" id="platform_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-neutral-800 dark:text-white">
                                    <option value="">{{__('Select platform')}}</option>
                                    @foreach($platforms as $platform)
                                        <option value="{{ $platform->id }}">{{ $platform->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="mt-3">
                            <button type="button" id="addMoreProfiles" class="text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                                <i class="fas fa-plus-circle mr-1"></i> {{__('Add another profile')}}
                            </button>
                        </div>
                        <div id="additionalProfiles" class="mt-4">
                            <!-- Additional social profiles will be added here dynamically -->
                        </div>
                    </div>

                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring focus:ring-blue-200 transition ease-in-out duration-150">
                        {{__('Create')}}
                    </button>
                </form>

                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        let profileCount = 1;
                        
                        document.getElementById('addMoreProfiles').addEventListener('click', function() {
                            const profilesContainer = document.getElementById('additionalProfiles');
                            const newProfileDiv = document.createElement('div');
                            newProfileDiv.className = 'grid grid-cols-1 md:grid-cols-2 gap-4 mt-4 pt-4 border-t border-gray-200 dark:border-neutral-700';
                            newProfileDiv.innerHTML = `
                                <div>
                                    <label for="social_username_${profileCount}" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{__('Username')}}</label>
                                    <input type="text" name="social_usernames[]" id="social_username_${profileCount}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-neutral-800 dark:text-white" placeholder="{{__('Enter social media username')}}">
                                </div>
                                <div>
                                    <label for="platform_id_${profileCount}" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{__('Platform')}}</label>
                                    <select name="platform_ids[]" id="platform_id_${profileCount}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-neutral-800 dark:text-white">
                                        <option value="">{{__('Select platform')}}</option>
                                        @foreach($platforms as $platform)
                                            <option value="{{ $platform->id }}">{{ $platform->name }}</option>
                                        @endforeach
                                    </select>
                                    <button type="button" class="remove-profile text-sm text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 mt-2">
                                        <i class="fas fa-trash mr-1"></i> {{__('Remove')}}
                                    </button>
                                </div>
                            `;
                            
                            profilesContainer.appendChild(newProfileDiv);
                            
                            // Add event listener to the new remove button
                            newProfileDiv.querySelector('.remove-profile').addEventListener('click', function() {
                                profilesContainer.removeChild(newProfileDiv);
                            });
                            
                            profileCount++;
                        });
                    });
                </script>
            </div>
        </div>
    </div>
</x-layouts.app>

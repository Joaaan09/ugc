<x-layouts.app :title=" __('Instagram Metrics')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <div class="grid auto-rows-min gap-4 md:grid-cols-3">
            <div
                class="flex items-center justify-center aspect-video rounded-xl border border-purple-300 bg-white shadow-lg dark:bg-neutral-900 dark:border-neutral-700 hover:shadow-xl transition-shadow duration-300">
                <div class="text-center space-y-2">
                    <p class="text-base font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wide">
                        {{$instagramMetrics->name}}
                    </p>
                    <p class="text-5xl font-extrabold text-purple-600 dark:text-purple-400">
                        <flux:avatar size="xl" src="{{ $instagramMetrics->profile_picture_url !== '' ? asset('storage/' . $instagramMetrics->profile_picture) : asset('storage/images/placeholder-profile.png') }}" />                    </p>
                    <div class="h-1 w-10 mx-auto bg-purple-300 rounded-full"></div>
                </div>
            </div>
            <div
                class="relative aspect-video overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
                <x-placeholder-pattern
                    class="absolute inset-0 size-full stroke-gray-900/20 dark:stroke-neutral-100/20" />
            </div>
        </div>
        
    </div>
</x-layouts.app>

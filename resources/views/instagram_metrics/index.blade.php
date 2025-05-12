<x-layouts.app :title="__('Influencer')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <div class="grid auto-rows-min gap-4 md:grid-cols-3">
            <div
                class="flex items-center justify-center aspect-video rounded-xl border border-purple-300 bg-white shadow-lg dark:bg-neutral-900 dark:border-neutral-700 hover:shadow-xl transition-shadow duration-300">
                <div class="text-center space-y-2">
                    <p class="text-base font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wide">
                        Total Influencers
                    </p>
                    <p class="text-5xl font-extrabold text-purple-600 dark:text-purple-400">
                        {{ $influencers->count() }}
                    </p>
                    <div class="h-1 w-10 mx-auto bg-purple-300 rounded-full"></div>
                </div>
            </div>
            <div
                class="relative aspect-video overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
                <x-placeholder-pattern
                    class="absolute inset-0 size-full stroke-gray-900/20 dark:stroke-neutral-100/20" />
            </div>
            <div
                class="relative aspect-video overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
                <x-placeholder-pattern
                    class="absolute inset-0 size-full stroke-gray-900/20 dark:stroke-neutral-100/20" />
            </div>
        </div>
        <div
            class="relative h-full flex-1 overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 p-6">
            <h2 class="text-xl font-semibold text-neutral-800 dark:text-white mb-4">Listado de Influencers</h2>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left border-separate border-spacing-y-2">
                    <thead>
                        <tr class="text-neutral-600 dark:text-neutral-300">
                            <th class="py-3 px-4">Foto</th>
                            <th class="py-3 px-4">Nombre</th>
                            <th class="py-3 px-4">Plataformas</th>
                            <th class="py-3 px-4">Seguidores Totales</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($influencers as $influencer)
                            <tr class="bg-white dark:bg-neutral-800 shadow-sm rounded-md">
                                <td class="py-2 px-4">
                                    <div class="w-[50px] h-[50px]">
                                        <a href="{{route('instagram.show', $influencer->id)}}"
                                            class="w-full h-full rounded-full overflow-hidden">
                                        <img src="{{ $influencer->profile_picture_url !== '' ? asset('storage/' . $influencer->profile_picture_url) : asset('storage/images/placeholder-profile.png') }}"
                                            alt="Foto de {{ $influencer->name }}"
                                            class="w-full h-full rounded-full object-cover"
                                            style="width: 50px; height: 50px;">
                                        </a>
                                    </div>
                                </td>
                                <td class="py-2 px-4 font-medium text-neutral-800 dark:text-white">
                                    {{ $influencer->name }}
                                </td>
                                <td class="py-2 px-4 space-x-1">
                                    @foreach ($influencer->socialProfiles as $profile)
                                        <a href="{{ $profile->profile_url ?? '#' }}" target="_blank"
                                            class="text-indigo-600 hover:underline">
                                            {{ $profile->platform->name }}
                                        </a>{{ !$loop->last ? ',' : '' }}
                                    @endforeach
                                </td>
                                <td class="py-2 px-4 text-neutral-700 dark:text-neutral-300">
                                    {{ $influencer->socialProfiles->sum('followers_count') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-layouts.app>

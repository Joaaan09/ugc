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
                class="flex items-center justify-center aspect-video rounded-xl border border-purple-300 bg-white shadow-lg dark:bg-neutral-900 dark:border-neutral-700 hover:shadow-xl transition-shadow duration-300">
                <div class="text-center space-y-2">
                    <p class="text-base font-semibold text-gray-500 dark:text-gray-300 uppercase tracking-wide">
                        {{__('Create Influencer')}}
                    </p>
                    <flux:button variant="primary" href="{{ route('influencer.create') }}">
                        {{ __('Create') }}
                    </flux:button>
                    <div class="h-1 w-10 mx-auto bg-purple-300 rounded-full"></div>
                </div>
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
                            <th class="py-3 px-4">Acciones</th> {{-- NUEVA COLUMNA --}}
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($influencers as $influencer)
                            <tr class="bg-white dark:bg-neutral-800 shadow-sm rounded-md">
                                <td class="py-2 px-4">
                                    <div class="w-[50px] h-[50px]">
                                        <img src="{{ $influencer->profile_picture_url !== '' ? $influencer->profile_picture_url : asset('storage/img/influencer/placeholder-profile.png') }}"
                                            alt="Foto de {{ $influencer->name }}"
                                            class="w-full h-full rounded-full object-cover"
                                            style="width: 50px; height: 50px;">
                                    </div>
                                </td>
                                <td class="py-2 px-4 font-medium text-neutral-800 dark:text-white">
                                    {{ $influencer->name }}
                                </td>
                                <td class="py-2 px-4 space-x-1">
                                    <flux:avatar.group class="**:ring-zinc-100 dark:**:ring-zinc-800">
                                    @foreach ($influencer->socialProfiles as $profile)
                                        <a href="{{ $profile->profile_url ?? '#' }}" target="_blank"
                                            class="text-indigo-600 hover:underline">
                                            <flux:avatar circle src="{{ asset('storage/img/platform/' . Str::lower($profile->platform->name) . '.png') }}" />
                                        </a>
                                    @endforeach
                                    </flux:avatar.group>
                                </td>
                                <td class="py-2 px-4 text-neutral-700 dark:text-neutral-300">
                                    {{ $influencer->socialProfiles->sum('followers_count') }}
                                </td>
                                <td class="py-2 px-4 space-y-1">
                                    {{-- BOTÓN VER MÉTRICAS TWITCH --}}
                                    @php
                                        $twitchProfile = $influencer->socialProfiles->firstWhere('platform.name', 'Twich');
                                    @endphp

                                    @if ($twitchProfile)
<a href="{{ route('influencer.twitch', $influencer->id) }}"
   class="text-purple-600 hover:underline">
   Ver métricas Twitch
</a>
                                    @else
                                        <span class="text-gray-400 italic">Sin Twitch</span>
                                    @endif

                                    {{-- BOTÓN ELIMINAR --}}
                                    <form action="{{ route('influencer.destroy', $influencer->id) }}" method="POST" class="mt-1">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:underline text-sm">
                                            {{ __('Delete') }}
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-layouts.app>

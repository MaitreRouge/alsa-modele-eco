<?php

use App\Models\User;
use Carbon\Carbon;
use Faker\Provider\Lorem;
use Illuminate\Support\Facades\Cookie;

$user = User::fromToken(Cookie::get("token"));
?>
@extends("layouts.layout",
[
    "pageTitle" => "Changelogs - Main",
    "activate" => 3
])

@section("main")

    <div class="border-b border-gray-200 bg-white px-4 pb-5 sm:px-6">
        <div class="-ml-4 -mt-4 flex flex-wrap items-center justify-between sm:flex-nowrap">
            <div class="ml-4 mt-4">
                <h3 class="text-base font-semibold leading-6 text-gray-900">Changelogs</h3>
                <p class="mt-1 text-sm text-gray-500">Sur cette page se trouve le résumé de tous les changelogs créés.
                    Seuls les administrateurs peuvent en créer mais tout le monde peut les consulter</p>
            </div>
            @if ($user->role === "admin")
                <div class="ml-4 mt-4 flex-shrink-0">
                    <a href="/changelog/new" type="button"
                       class="relative inline-flex items-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                        Créer un nouveau changelog
                    </a>
                </div>
            @endif
        </div>
    </div>

    @foreach ($changelogs as $changelog)
        <div class="pt-5">
            <div class="space-y-12">
                <div
                    class="grid grid-cols-1 gap-x-8 gap-y-10 border-b border-gray-900/10 pb-12 md:grid-cols-3 px-4 pt-5">
                    <div>
                        <h2 class="text-base font-semibold leading-7 text-gray-900"> {{ $changelog->titre }}</h2>
                        <p class="mt-1 text-sm leading-6 text-gray-600">{{ $changelog->description }}</p>
                    </div>

                    <div class="max-w-2xl gap-x-6 gap-y-8 md:col-span-2">
                        <div class="flow-root">
                            <ul role="list" class="-mb-8">
                                <li>
                                    <div class="relative pb-8">
                                        @if (count($changelog->fetchAllHistories()) > 1)
                                            <span class="absolute left-4 top-4 -ml-px h-full w-0.5 bg-gray-200"
                                                  aria-hidden="true"></span>
                                        @endif
                                        <div class="relative flex space-x-3">
                                            <div>
                                                <span
                                                    class="h-8 w-8 rounded-full bg-gray-400 flex items-center justify-center ring-8 ring-white">
                                                    <svg class="h-5 w-5 text-white" viewBox="0 0 20 20"
                                                         fill="currentColor" aria-hidden="true">
                                                    <path
                                                        d="M10 8a3 3 0 100-6 3 3 0 000 6zM3.465 14.493a1.23 1.23 0 00.41 1.412A9.957 9.957 0 0010 18c2.31 0 4.438-.784 6.131-2.1.43-.333.604-.903.408-1.41a7.002 7.002 0 00-13.074.003z"/>
                                                    </svg>
                                                </span>
                                            </div>
                                            <div class="flex min-w-0 flex-1 justify-between space-x-4 pt-1.5">
                                                <div>
                                                    <p class="text-sm text-gray-500">Publié par
                                                        <a class="font-medium text-gray-900">{{ User::find($changelog->uid)->getPublicName() }}</a>
                                                    </p>
                                                </div>
                                                {{--                                                @dump($changelog->created_at)--}}
                                                <div class="whitespace-nowrap text-right text-sm text-gray-500">
                                                    {{--                                                    <time>Sep 20</time>--}}
                                                    {{ (Carbon::create($changelog->created_at))->isoFormat("D MMMM") }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                @for($i = 0; $i < count($changelog->fetchAllHistories()); $i++)
                                        <?php $history = $changelog->fetchAllHistories()[$i]; ?>
                                    <li>
                                        <div class="relative pb-8">
                                            @if ($i < (count($changelog->fetchAllHistories()) - 1))
                                                <span class="absolute left-4 top-4 -ml-px h-full w-0.5 bg-gray-200"
                                                      aria-hidden="true"></span>
                                            @endif
                                            <div class="relative flex space-x-3">
                                                <div>
                                                    @switch($history->type)
                                                        @case("edition")
                                                            <span class="h-8 w-8 rounded-full bg-blue-500 flex items-center justify-center ring-8 ring-white">
                                                                <svg fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                                                     stroke="currentColor" class="h-5 w-5 text-white">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"/>
                                                                </svg>
                                                            </span>
                                                        @break
                                                        @case("deletion")
                                                            <span class="h-8 w-8 rounded-full bg-red-500 flex items-center justify-center ring-8 ring-white">
                                                                <svg fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                                                     stroke="currentColor" class="h-5 w-5 text-white">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/>
                                                                </svg>
                                                            </span>
                                                        @break
                                                        @case("creation")
                                                            <span class="h-8 w-8 rounded-full bg-green-500 flex items-center justify-center ring-8 ring-white">
                                                                <svg fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                                                     stroke="currentColor" class="h-5 w-5 text-white">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                                                                </svg>
                                                            </span>
                                                        @break
                                                    @endswitch


                                                </div>
                                                <div class="flex min-w-0 flex-1 justify-between space-x-4 pt-1.5">
                                                    <div>
                                                        @switch($history->type)
                                                            @case("edition")
                                                                @foreach($history->getChanges() as $change)

                                                                <p class="text-sm text-gray-500">
                                                                    Valeur de
                                                                    <a class="font-medium text-gray-900">{{ $change }}</a>
                                                                    modifié de
                                                                    <a class="font-medium text-gray-900">{{ $history->getOldPrestation()->$change??'null' }}</a>
                                                                    à
                                                                    <a class="font-medium text-gray-900">{{ $history->getNewPrestation()->$change??'null' }}</a>
                                                                    @if ($change !== "label")
                                                                    sur
                                                                    <a class="font-medium text-gray-900">{{ $history->getSafeLabel() }}</a>
                                                                    @endif
                                                                </p>
                                                                @endforeach
                                                                @break
                                                            @case("deletion")
                                                                <p class="text-sm text-gray-500">
                                                                    Suppression de la prestation :
                                                                    <a class="font-medium text-gray-900">{{ $history->getSafeLabel() }}</a>
                                                                </p>
                                                                @break
                                                            @case("creation")
                                                                <p class="text-sm text-gray-500">
                                                                    Création de la prestation :
                                                                    <a class="font-medium text-gray-900">{{ $history->getSafeLabel() }}</a>
                                                                </p>
                                                                @break
                                                        @endswitch

                                                    </div>
                                                    <div class="whitespace-nowrap text-right text-sm text-gray-500">
                                                        {{ (Carbon::create($history->created_at))->isoFormat("D MMMM") }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                @endfor
                            </ul>
                        </div>

                    </div>
                </div>

            </div>

        </div>
    @endforeach

@endsection

<?php

use App\Models\User;
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


    <form class="pt-5">
        <div class="space-y-12">
            <div
                class="grid grid-cols-1 gap-x-8 gap-y-10 border-b border-gray-900/10 pb-12 md:grid-cols-3 px-4 pt-5">
                <div>
                    <h2 class="text-base font-semibold leading-7 text-gray-900">Changelog name</h2>
                    <p class="mt-1 text-sm leading-6 text-gray-600">{{ Lorem::text(400) }}</p>
                </div>

                <div class="max-w-2xl gap-x-6 gap-y-8 md:col-span-2">
                    <div class="flow-root">
                        <ul role="list" class="-mb-8">
                            <li>
                                <div class="relative pb-8">
                                    <span class="absolute left-4 top-4 -ml-px h-full w-0.5 bg-gray-200"
                                          aria-hidden="true"></span>
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
                                                    <a class="font-medium text-gray-900">{{ User::first()->getPublicName() }}</a>
                                                </p>
                                            </div>
                                            <div class="whitespace-nowrap text-right text-sm text-gray-500">
                                                <time>Sep 20</time>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            @for($j = 0; $j < 5; $j++)
                                <li>
                                    <div class="relative pb-8">
                                        @if ($j !== 4)
                                            <span class="absolute left-4 top-4 -ml-px h-full w-0.5 bg-gray-200"
                                                  aria-hidden="true"></span>
                                        @endif
                                        <div class="relative flex space-x-3">
                                            <div>
                                                <span
                                                    class="h-8 w-8 rounded-full bg-blue-500 flex items-center justify-center ring-8 ring-white">
{{--                                                    <svg class="h-5 w-5 text-white" viewBox="0 0 20 20"--}}
                                                    {{--                                                         fill="currentColor" aria-hidden="true">--}}
                                                    {{--                                                        <path--}}
                                                    {{--                                                            d="M1 8.25a1.25 1.25 0 112.5 0v7.5a1.25 1.25 0 11-2.5 0v-7.5zM11 3V1.7c0-.268.14-.526.395-.607A2 2 0 0114 3c0 .995-.182 1.948-.514 2.826-.204.54.166 1.174.744 1.174h2.52c1.243 0 2.261 1.01 2.146 2.247a23.864 23.864 0 01-1.341 5.974C17.153 16.323 16.072 17 14.9 17h-3.192a3 3 0 01-1.341-.317l-2.734-1.366A3 3 0 006.292 15H5V8h.963c.685 0 1.258-.483 1.612-1.068a4.011 4.011 0 012.166-1.73c.432-.143.853-.386 1.011-.814.16-.432.248-.9.248-1.388z"/>--}}
                                                    {{--                                                    </svg>--}}
                                                    <svg fill="none"
                                                         viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                         class="h-5 w-5 text-white">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                              d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"/>
                                                    </svg>

                                                </span>
                                            </div>
                                            <div class="flex min-w-0 flex-1 justify-between space-x-4 pt-1.5">
                                                <div>
                                                    <p class="text-sm text-gray-500">
                                                        Valeur de
                                                        <a class="font-medium text-gray-900">${property}</a>
                                                        modifié de
                                                        <a class="font-medium text-gray-900">${old}</a>
                                                        à
                                                        <a class="font-medium text-gray-900">${new}</a>
                                                        sur
                                                        <a class="font-medium text-gray-900">${prestation}</a>
                                                    </p>
                                                </div>
                                                <div class="whitespace-nowrap text-right text-sm text-gray-500">
                                                    <time datetime="2020-09-22">Sep 22</time>
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

    </form>

@endsection

<?php

use App\Models\Prestation;

?>
@extends("layouts.layout",
[
    "pageTitle" => "Prestations - Ajout d'options",
    "subActive" => $subActive,
    "activate" => 2
])
@section("main")

        <form method="post" action="/prestations/addOptions">
            @csrf
            <div class="px-4 sm:px-6 lg:px-8">
                <div class="mt-2 flow-root">
                    <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                        <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                            <table class="min-w-full">
                                <thead class="bg-white">
                                <tr>
                                    <th scope="col"
                                        class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-3">
                                        Prestation
                                    </th>
                                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">
                                        Version
                                    </th>
                                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">
                                        Frais
                                        Instal
                                    </th>
                                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">
                                        Prix
                                        Mensuel
                                    </th>
                                    <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-3">
                                        <span class="sr-only">Ajout</span>
                                    </th>
                                </tr>
                                </thead>
                                <tbody class="bg-white">
                                @foreach($parents as $parent)
                                    <tr class="border-t border-gray-200">
                                        <th colspan="5" scope="colgroup"
                                            class="bg-gray-200 py-2 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-3 relative">
                                            {{ $parent->label }}
                                        </th>

                                    </tr>
                                    @foreach($prestations[$parent->id] as $prestation)
                                        <tr class="border-t border-gray-200">
                                            <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-3">
                                                <input name="opt-{{$prestation->id}}" type="checkbox"
                                                       class="mr-2 w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                                {{ $prestation->label }}
                                                    <?= $prestation->showBadges() ?>
                                            </td>
                                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                                v{{ $prestation->version }}</td>
                                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ Prestation::staticFormatPrice($prestation->prixFAS)??"-" }}</td>
                                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ Prestation::staticFormatPrice($prestation->prixMensuel)??"-" }}</td>
                                        </tr>
                                    @endforeach
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <button type="submit"
                    class="mt-6 ml-4 disabled:bg-white disabled:text-white rounded-md bg-green-100 px-3 py-2 text-sm font-semibold text-green-600 shadow-sm disabled:shadow-none hover:bg-green-200">
                Ajout des options
            </button>
        </form>

@endsection

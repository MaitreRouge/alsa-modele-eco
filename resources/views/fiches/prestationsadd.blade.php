@extends("layouts.fiche",
[
    "pageTitle" => "Fiche Entreprisee - ".ucfirst($name),
    "subActive" => $subActive
])
@section("fiche")

    <form method="post">
        @csrf
        <div class="space-y-12">

            <div class="grid grid-cols-1 gap-x-8 gap-y-10 border-b border-gray-900/10 pb-12 md:grid-cols-3">
                <div class="grid max-w-2xl grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6 md:col-span-2">
                    <div class="sm:col-span-3">
                        <label for="tri"
                               class="block text-sm font-medium leading-6 text-gray-900">Categorie</label>
                        <select id="tri" name="tri"
                                class="mt-2 block w-full rounded-md border-0 py-1.5 pl-3 pr-10 text-gray-900 ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-indigo-600 sm:text-sm sm:leading-6">
                            @foreach($categories as $c)
                                <option value="{{ $c->id }}">{{ $c->label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="sm:col-span-2">
                        <label for="button" class="block text-sm font-medium leading-6 text-gray-900 opacity-0">Rechercher</label>
                        <button id="button" type="submit"
                                class="mt-2 rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                            Trier
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    @if (count($prestations) > 0)
        <div class="px-4 sm:px-6 lg:px-8">
            <div class="mt-8 flow-root">
                <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                        <table class="min-w-full">
                            <thead class="bg-white">
                            <tr>
                                <th scope="col"
                                    class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-3">
                                    Prestation
                                </th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Frais
                                    Instal
                                </th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Prix
                                    Brut
                                </th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Prix
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
                                        class="bg-gray-50 py-2 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-3">
                                        {{ $parent->label }}
                                    </th>
                                </tr>
                                @foreach($prestations[$parent->id] as $prestation)
                                    <tr class="border-t border-gray-200">
                                        <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-3">{{ $prestation->label }}</td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $prestation->price("prixFraisInstalation")??"-" }}</td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $prestation->price("prixBrut")??"-" }}</td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $prestation->price("prixMensuel")??"-" }}</td>
                                        <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-3">
                                            <a href="add/{{ $prestation->id }}" class="text-indigo-600 hover:text-indigo-900">Ajouter</a>
                                        </td>
                                    </tr>
                                @endforeach
                            @endforeach

                            <!-- More people... -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    @endif

@endsection

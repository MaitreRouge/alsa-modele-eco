@php use App\Models\Prestation; @endphp
@extends("layouts.prestations",
[
    "pageTitle" => "Prestations - ".ucfirst($name),
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
                                <option
                                    value="{{ $c->id }}" {{ (($main->id??0) == $c->id)?"selected":"" }}>{{ $c->label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="sm:col-span-3">
                        <label for="button" class="block text-sm font-medium leading-6 text-gray-900 opacity-0">Rechercher</label>
                        <button id="button" type="submit"
                                class="mt-2 rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                            Trier
                        </button>
                        @if (!empty($main->id))
                            <a type="submit" href="{{ strtolower($name) }}/{{ $main->id }}/new"
                               class="mt-2 ml-2 rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                                Créer
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </form>

    @if(!empty($main->note))
        <div class="rounded-md bg-yellow-50 p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd"
                              d="M8.485 2.495c.673-1.167 2.357-1.167 3.03 0l6.28 10.875c.673 1.167-.17 2.625-1.516 2.625H3.72c-1.347 0-2.189-1.458-1.515-2.625L8.485 2.495zM10 5a.75.75 0 01.75.75v3.5a.75.75 0 01-1.5 0v-3.5A.75.75 0 0110 5zm0 9a1 1 0 100-2 1 1 0 000 2z"
                              clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-yellow-800">Attention necessaire sur la catégorie
                        "{{ $main->label }}"</h3>
                    <div class="mt-2 text-sm text-yellow-700">
                            <?= str_replace("\n", "<br>", $main->note) ?>
                    </div>
                </div>
            </div>
        </div>
    @endif

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
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">
                                    Version
                                </th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Frais
                                    Instal
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
                                        class="bg-gray-200 py-2 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-3 relative">
                                        {{ $parent->label }}
                                        @if(!empty($parent->note))
                                            <svg class="h-5 w-5 text-yellow-400" style="display: inline"
                                                 viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                <path fill-rule="evenodd"
                                                      d="M8.485 2.495c.673-1.167 2.357-1.167 3.03 0l6.28 10.875c.673 1.167-.17 2.625-1.516 2.625H3.72c-1.347 0-2.189-1.458-1.515-2.625L8.485 2.495zM10 5a.75.75 0 01.75.75v3.5a.75.75 0 01-1.5 0v-3.5A.75.75 0 0110 5zm0 9a1 1 0 100-2 1 1 0 000 2z"
                                                      clip-rule="evenodd"/>
                                            </svg>
                                        @endif
                                        <a href="bulk-edit/{{$parent->id}}" class="absolute right-0 pr-4 text-indigo-600 hover:text-indigo-900">Modification de masse</a>
                                    </th>

                                </tr>
                                @foreach($prestations[$parent->id] as $prestation)
                                    <tr class="border-t border-gray-200">
                                        <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-3">
                                            {{ $prestation->label }}
                                            @if(!empty($prestation->note))
                                                <svg class="h-5 w-5 text-purple-500" style="display: inline" fill="none"
                                                     stroke-width="1.5" viewBox="0 0 24 24" stroke="currentColor"
                                                     aria-hidden="true">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                          d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z"/>
                                                </svg>
                                            @endif
                                        </td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                            v{{ $prestation->version }}</td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ Prestation::staticFormatPrice($prestation->prixFraisInstalation)??"-" }}</td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ Prestation::staticFormatPrice($prestation->prixMensuel)??"-" }}</td>
                                        <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-3">
                                            <a href="edit/{{$prestation->id}}"
                                               class="text-indigo-600 hover:text-indigo-900">Modifier</a>
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

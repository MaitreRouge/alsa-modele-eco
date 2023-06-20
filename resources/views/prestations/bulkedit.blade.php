@extends("layouts.prestations",
[
    "pageTitle" => "Prestations - ".ucfirst($name),
    "subActive" => $subActive
])
@section("fiche")

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

    <form method="post">
        @csrf
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
                                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">
                                        Frais
                                        Instal
                                    </th>
                                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">
                                        Prix
                                        Mensuel
                                    </th>
                                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">
                                        Prix
                                        Brut
                                    </th>
                                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">
                                        Prix vente
                                    </th>
                                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">
                                        Note
                                    </th>
                                </tr>
                                </thead>
                                <tbody class="bg-white">
                                @foreach($parents as $parent)
                                    <tr class="border-t border-gray-200">
                                        <th colspan="7" scope="colgroup"
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
                                        </th>

                                    </tr>
                                    @foreach($prestations[$parent->id] as $prestation)
                                        <tr class="border-t border-gray-200">
                                            <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-3">
                                                <div>
                                                    <input type="text" name="presta-{{ $prestation->id }}-label"
                                                           id="presta-{{ $prestation->id }}-label"
                                                           class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                                           value="{{ $prestation->label }}"
                                                    >
                                                </div>
                                            </td>
                                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                                v{{ $prestation->version }}</td>
                                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                                <div>
                                                    <input type="number"
                                                           name="presta-{{ $prestation->id }}-prixFraisInstalation"
                                                           id="presta-{{ $prestation->id }}-prixFraisInstalation"
                                                           class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                                           value="{{ $prestation->prixFraisInstalation }}"
                                                    >
                                                </div>
                                            </td>
                                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                                <div>
                                                    <input type="number" name="presta-{{ $prestation->id }}-prixMensuel"
                                                           id="presta-{{ $prestation->id }}-prixMensuel"
                                                           class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                                           value="{{ $prestation->prixMensuel }}"
                                                    >
                                                </div>
                                            </td>
                                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                                <div>
                                                    <input type="number" name="presta-{{ $prestation->id }}-prixBrut"
                                                           id="presta-{{ $prestation->id }}-prixBrut"
                                                           class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                                           value="{{ $prestation->prixBrut }}"
                                                    >
                                                </div>
                                            </td>
                                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                                <div>
                                                    <input type="checkbox"
                                                           name="presta-{{ $prestation->id }}-needPrixVente"
                                                           id="presta-{{ $prestation->id }}-needPrixVente"
                                                           class="rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 sm:text-sm sm:leading-6"
                                                        {{ ($prestation->needPrixVente)?"checked":"" }}
                                                    >
                                                </div>
                                            </td>
                                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                                <div>
                                                    <label for="email" class="sr-only">Email</label>
                                                    <textarea type="text" name="presta-{{ $prestation->id }}-note"
                                                              id="presta-{{ $prestation->id }}-note"
                                                              class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                                              placeholder="you@example.com"> {{ $prestation->note }} </textarea>
                                                </div>
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

        <div class="mt-6 flex items-center justify-end gap-x-6">
            <a href="/prestations/{{ strtolower($parent->rootCategory()->label) }}?tri={{ $parent->parentCategory()->id }}"
               type="button" class="text-sm font-semibold leading-6 text-gray-900">Annuler</a>
            <button type="submit"
                    class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                Sauvegarder
            </button>
        </div>
    </form>

@endsection

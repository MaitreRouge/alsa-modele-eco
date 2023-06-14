@extends("layouts.fiche",
[
    "pageTitle" => "Fiche Entreprise - Édition d'une prestation",
    "subActive" => $subActive
])
@section("fiche")

    <div id="error-message" hidden class="relative z-10" aria-labelledby="modal-title" role="dialog" aria-modal="true">

        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>

        <div class="fixed inset-0 z-10 overflow-y-auto">
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <div
                    class="relative transform overflow-hidden rounded-lg bg-white px-4 pb-4 pt-5 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:p-6">
                    <div class="sm:flex sm:items-start">
                        <div
                            class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                 stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/>
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
                            <h3 class="text-base font-semibold leading-6 text-gray-900" id="modal-title">Supprimer la
                                prestation ?</h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">
                                    Certes, il est facile et rapide de ré-ajouter cette prestation au devis à nouveau mais il se peut que les offres promotionnelles / commerciales que beneficie cette prestation ne soit plus en vigueur quand il sera réajouté.
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
                        <a href="../delete/{{ $devis->id }}" type="button"
                           class="inline-flex w-full justify-center rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 sm:ml-3 sm:w-auto">Supprimer définitivement</a>
                        <button type="button" id="delete-back"
                                class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">
                            Retour en arrière
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <form method="POST" autocomplete="off">
        @csrf
        <div class="space-y-12">
            <div class="grid gap-x-8 gap-y-10  border-b border-gray-900/10 pb-12 ">
                <div>
                    <h2 class="text-base font-semibold leading-7 text-gray-900">Informations générales de la
                        prestation</h2>
                    <p class="mt-1 text-sm leading-6 text-gray-600">Sur cet écran, il est possible de modifier
                        l'intégralité des prestations avant de les entrer dans le devis.</p>
                </div>
                <div class="grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6 md:col-span-2">

                    @if(!empty($prestation->note))
                        <div class="sm:col-span-6">
                            <div class="rounded-md bg-blue-50 p-4">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor"
                                             aria-hidden="true">
                                            <path fill-rule="evenodd"
                                                  d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a.75.75 0 000 1.5h.253a.25.25 0 01.244.304l-.459 2.066A1.75 1.75 0 0010.747 15H11a.75.75 0 000-1.5h-.253a.25.25 0 01-.244-.304l.459-2.066A1.75 1.75 0 009.253 9H9z"
                                                  clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <h3 class="text-sm font-medium text-blue-700">Note sur la prestation :</h3>
                                        <div class="mt-2 text-sm text-blue-600">
                                            <p>{{ $prestation->note }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="sm:col-span-4">
                        <label for="label" class="block text-sm font-medium leading-6 text-gray-900">Label</label>
                        <div class="mt-2">
                            <input type="text" id="label" value="{{ $devis->customName??$prestation->label }}" {{ ($prestation->id === 0)?"":"disabled"}} name="customName"
                                   class="disabled:bg-slate-100 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                        </div>
                    </div>
                    <div class="sm:col-span-2"></div>
                    <div class="sm:col-span-1"></div>
                    <div class="sm:col-span-1">
                        <label for="prixfas" class="block text-sm font-medium leading-6 text-gray-900">Prix FAS</label>
                        <div class="mt-2">
                            <input type="text" name="prixfas" id="prixfas"
                                   value="{{ $devis->prixFraisInstalation??$prestation->prixFraisInstalation }}"
                                   class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                        </div>
                    </div>
                    <div class="sm:col-span-1">
                        <label for="prixbrut" class="block text-sm font-medium leading-6 text-gray-900">Prix
                            brut</label>
                        <div class="mt-2">
                            <input type="text" name="prixbrut" id="prixbrut"
                                   value="{{ $devis->prixBrut??$prestation->prixBrut }}"
                                   class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                        </div>
                    </div>
                    <div class="sm:col-span-1">
                        <label for="prixmensuel" class="block text-sm font-medium leading-6 text-gray-900">Prix
                            mensuel</label>
                        <div class="mt-2">
                            <input type="text" name="prixmensuel" id="prixmensuel"
                                   value="{{ $devis->prixMensuel??$prestation->prixMensuel }}"
                                   class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                        </div>
                    </div>
                    <div class="sm:col-span-2">

                    </div>
                    <div class="sm:col-span-1">
                        <label for="qte" class="block text-sm font-medium leading-6 text-gray-900">Quantité</label>
                        <div class="mt-2">
                            <input type="text" name="qte" id="qte" value="{{ $devis->quantite }}"
                                   class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                        </div>
                    </div>
                    <div class="sm:col-span-1">
                        <label for="totalfas" class="block text-sm font-medium leading-6 text-gray-900">Total
                            FAS</label>
                        <div class="mt-2">
                            <input type="text" id="totalfas" value="" disabled
                                   class="bg-slate-100 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                        </div>
                    </div>
                    <div class="sm:col-span-1">
                        <label for="totalbrut" class="block text-sm font-medium leading-6 text-gray-900">Total
                            brut</label>
                        <div class="mt-2">
                            <input type="text" id="totalbrut" value="" disabled
                                   class="bg-slate-100 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                        </div>
                    </div>
                    <div class="sm:col-span-1">
                        <label for="totalmensuel" class="block text-sm font-medium leading-6 text-gray-900">Total
                            mensuel</label>
                        <div class="mt-2">
                            <input type="text" id="totalmensuel" value="" disabled
                                   class="bg-slate-100 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                        </div>
                    </div>
                    <div class="sm:col-span-1">

                    </div>
                    <div class="sm:col-span-1">
                        <label for="total" class="block text-sm font-medium leading-6 text-gray-900">Total</label>
                        <div class="mt-2">
                            <input type="text" id="total" value="" disabled
                                   class="bg-slate-100 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                        </div>
                    </div>


                </div>
            </div>

        </div>

        <div class="mt-6 flex items-center justify-end gap-x-6">
            <a href=".." type="button" class="text-sm font-semibold leading-6 text-gray-900">Retour</a>
            <a type="button" id="delete"
               class="rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-600">
                Supprimer
            </a>
            <button type="submit"
                    class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                Modifier
            </button>
        </div>
    </form>


    <script>
        jQuery(document).ready(function () {
            var $ = jQuery;

            $("#delete").click(function () {
                $("#error-message").show()
            });

            $("#delete-back").click(function () {
                $("#error-message").hide();
            });

        });
    </script>

@endsection

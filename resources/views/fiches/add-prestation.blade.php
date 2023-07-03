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
                                    Certes, il est facile et rapide de ré-ajouter cette prestation au devis à nouveau
                                    mais il se peut que les offres promotionnelles / commerciales que beneficie cette
                                    prestation ne soit plus en vigueur quand il sera réajouté.
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
                        <a href="../delete/{{ $devis->id??null }}" type="button"
                           class="inline-flex w-full justify-center rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 sm:ml-3 sm:w-auto">Supprimer
                            définitivement</a>
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

                    <div class="sm:col-span-6">
                        <label for="label" class="block text-sm font-medium leading-6 text-gray-900">Label</label>
                        <div class="mt-2">
                            <input type="text" id="label" value="{{ $prestation->label }}" name="customName"
                                   {{ ($prestation->id === 0)?"":"disabled"}}
                                   class="disabled:bg-slate-100 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                        </div>
                    </div>
                    <div class="sm:col-span-1"></div>
                    <div class="sm:col-span-1">
                        <label for="prixfas" class="block text-sm font-medium leading-6 text-gray-900">Prix FAS</label>
                        <div class="mt-2">
                            <input type="number" step="0.01" name="prixfas" id="prixfas"
                                   value="{{ $prestation->prixFAS??0 }}" disabled
                                   class="disabled:bg-slate-100 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                        </div>
                    </div>
                    <div class="sm:col-span-1">
                        <label for="pdvfas" class="block text-sm font-medium leading-6 text-gray-900">Prix de vente
                            FAS</label>
                        <div class="mt-2">
                            <input type="number" step="0.01" name="pdvfas" id="pdvfas"
                                   placeholder="{{ $prestation->prixFAS??0 }}" value="{{ $devis->pdvFAS??"" }}"
                                   class="disabled:bg-slate-100 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                        </div>
                    </div>
                    <div class="sm:col-span-1">
                    </div>
                    <div class="sm:col-span-1">
                        <label for="prixmensuel" class="block text-sm font-medium leading-6 text-gray-900">Prix
                            mensuel</label>
                        <div class="mt-2">
                            <input type="number" step="0.01" name="prixmensuel" id="prixmensuel"
                                   value="{{ $prestation->prixMensuel??0 }}" disabled
                                   class="disabled:bg-slate-100 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                        </div>
                    </div>
                    <div class="sm:col-span-1">
                        <label for="pdvmensuel" class="block text-sm font-medium leading-6 text-gray-900">Prix de vente
                            mensuel</label>
                        <div class="mt-2">
                            <input type="number" step="0.01" name="pdvmensuel" id="pdvmensuel"
                                   placeholder="{{ $prestation->prixMensuel??0 }}" value="{{ $devis->pdvMensuel??"" }}"
                                   class="disabled:bg-slate-100 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                        </div>
                    </div>
                    <div class="sm:col-span-1">
                        <label for="qte" class="block text-sm font-medium leading-6 text-gray-900">Quantitée</label>
                        <div class="mt-2">
                            <input type="number" step="0.01" name="qte" id="qte"
                                   value="1"
                                   class="disabled:bg-slate-100 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                        </div>
                    </div>
                    <div class="sm:col-span-1">
                        <label for="totalprixfas" class="block text-sm font-medium leading-6 text-gray-900">Total prix
                            FAS</label>
                        <div class="mt-2">
                            <input type="number" step="0.01" name="totalprixfas" id="totalprixfas"
                                   value="" disabled
                                   class="disabled:bg-slate-100 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                        </div>
                    </div>
                    <div class="sm:col-span-1">
                        <label for="totalpdvfas" class="block text-sm font-medium leading-6 text-gray-900">Total prix de
                            vente FAS </label>
                        <div class="mt-2">
                            <input type="number" step="0.01" name="totalpdvfas" id="totalpdvfas"
                                   value="" disabled
                                   class="disabled:bg-slate-100 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                        </div>
                    </div>
                    <div class="sm:col-span-1">

                    </div>
                    <div class="sm:col-span-1">
                        <label for="totalprixmensuel" class="block text-sm font-medium leading-6 text-gray-900">Total
                            prix mensuel</label>
                        <div class="mt-2">
                            <input type="text" name="totalprixmensuel" id="totalprixmensuel"
                                   value="" disabled
                                   class="disabled:bg-slate-100 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                        </div>
                    </div>
                    <div class="sm:col-span-1">
                        <label for="totalpdvmensuel" class="block text-sm font-medium leading-6 text-gray-900">Total
                            prix de vente mensuel</label>
                        <div class="mt-2">
                            <input type="text" name="totalpdvmensuel" id="totalpdvmensuel"
                                   value="" disabled
                                   class="disabled:bg-slate-100 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                        </div>
                    </div>


                </div>
            </div>
            @if (count($prestation->getOptions()) > 0)
                <div class="grid gap-x-8 gap-y-10  border-b border-gray-900/10 pb-12 ">
                    <div>
                        <h2 class="text-base font-semibold leading-7 text-gray-900">Informations générales de la
                            prestation</h2>
                        <p class="mt-1 text-sm leading-6 text-gray-600">Sur cet écran, il est possible de modifier
                            l'intégralité des prestations avant de les entrer dans le devis.</p>
                    </div>

                    <div class="grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6 md:col-span-2">

                        <fieldset class="sm:col-span-5">
                            <div class="space-y-4">

                                @foreach($prestation->getOptions() as $option)
                                    <label id="opt-{{ $option->option_id }}-label"
                                           class="relative block cursor-pointer rounded-lg border bg-white px-6 py-4 shadow-sm focus:outline-none sm:flex sm:justify-between"
                                    >
                                        <input type="checkbox" name="opt-{{ $option->option_id }}" disabled
                                               id="opt-{{ $option->option_id }}" value="true" class="sr-only"
                                               aria-labelledby="opt-{{ $option->option_id }}-0-label"
                                               aria-describedby="opt-{{ $option->option_id }}-0-description-0 opt-{{ $option->option_id }}-0-description-1" {{ (!empty($devis) and $devis->isOptionSelected($option->option_id))?"checked":"" }} >
                                        <span class="flex items-center">
                                                <span class="flex flex-col text-sm">
                                                    <span id="opt-{{ $option->option_id }}-size-0-label"
                                                          class="font-medium text-gray-900">{{ $option->getPrestation()->label }} <?= $option->getPrestation()->showBadges() ?></span>
                                                    <span id="opt-{{ $option->option_id }}-size-0-description-0"
                                                          class="text-gray-500">
                                                        <span
                                                            class="block sm:inline">{{ $option->getPrestation()->note }}</span>
                                                    </span>
                                                </span>
                                            </span>
                                        <div class="flex-none">
                                            @if(!empty($option->getPrestation()->prixFAS))
                                                <span id="opt-{{ $option->option_id }}-size-0-description-1"
                                                      class="mt-2 flex text-sm sm:ml-4 sm:mt-0 sm:flex-col sm:text-right">
                                                <span
                                                    class="font-medium text-gray-900">{{ $option->getPrestation()->formatPrice("prixFAS") }}</span>
                                                <span class="ml-1 text-gray-500 sm:ml-0">une fois</span>
                                            </span>
                                            @endif
                                            @if(!empty($option->getPrestation()->prixMensuel))
                                                <span id="opt-{{ $option->option_id }}-size-0-description-2"
                                                      class="mt-2 flex text-sm sm:ml-4 sm:mt-0 sm:flex-col sm:text-right">
                                                <span
                                                    class="font-medium text-gray-900">{{ $option->getPrestation()->formatPrice("prixMensuel") }}</span>
                                                <span class="ml-1 text-gray-500 sm:ml-0">par mois</span>
                                            </span>
                                            @endif
                                        </div>
                                        <span id="opt-{{ $option->option_id }}-border"
                                                  class="pointer-events-none absolute -inset-px rounded-lg border-2"
                                              aria-hidden="true"></span>
                                    </label>

                                    <script>
                                        $(document).ready(function () {
                                            $('#opt-{{ $option->option_id }}').change(function () {
                                                changeColor_{{ $option->option_id }}();
                                            });

                                            changeColor_{{ $option->option_id }}();

                                            function changeColor_{{ $option->option_id }} () {
                                                if ($('#opt-{{ $option->option_id }}').is(":checked")) {
                                                    $('#opt-{{ $option->option_id }}-label').removeClass("border-gray-300")
                                                        .addClass("border-transparent border-indigo-600 ring-2 ring-indigo-600")
                                                    $('#opt-{{ $option->option_id }}-border').removeClass("border-2 border-transparent")
                                                        .addClass("border border-indigo-600")
                                                    return;
                                                }
                                                $('#opt-{{ $option->option_id }}-label')
                                                    .removeClass("border-transparent border-indigo-600 ring-2 ring-indigo-600")
                                                    .addClass("border-gray-300")
                                                $('#opt-{{ $option->option_id }}-border')
                                                    .removeClass("border border-indigo-600")
                                                    .addClass("border-2 border-transparent")
                                            }
                                        });
                                    </script>
                                @endforeach

                            </div>
                        </fieldset>
                    </div>
                </div>

        </div>
        @endif

        <div class="mt-6 flex items-center justify-end gap-x-6">
            <a href="../add" type="button" class="text-sm font-semibold leading-6 text-gray-900">Retour</a>
            @if (!empty($devis))
                <a type="button" id="delete"
                   class="rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-600">
                    Supprimer
                </a>
            @endif
            <button type="submit"
                    class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                @if (empty($devis))
                    Ajouter
                @else
                    Éditer
                @endif
            </button>
        </div>
    </form>


    <script>
        $(document).ready(function () {
            $("#qte").on("input", function () {
                calPrices();
            });
            $("#pdvfas").on("input", function () {
                calPrices();
            });
            $("#pdvmensuel").on("input", function () {
                calPrices();
            });

            calPrices();

            function calPrices() {
                let qteValue = $("#qte").val();

                let totalfas = qteValue * $("#prixfas").val();
                $("#totalprixfas").val(totalfas);

                let totalpdvfas = qteValue * ($("#pdvfas").val() ? $("#pdvfas").val() : $("#prixfas").val());
                $("#totalpdvfas").val(totalpdvfas);

                let totalmensuel = qteValue * $("#prixmensuel").val();
                $("#totalprixmensuel").val(totalmensuel);

                let totalpdvmensuel = qteValue * ($("#pdvmensuel").val() ? $("#pdvmensuel").val() : $("#prixmensuel").val());
                $("#totalpdvmensuel").val(totalpdvmensuel);
            }

            $("#delete").click(function () {
                $("#error-message").show()
            });
            $("#delete-back").click(function () {
                $("#error-message").hide();
            });
        });
    </script>

@endsection

@extends("layouts.fiche",
[
    "pageTitle" => "Fiche Entreprise - Ajout d'une prestation",
    "subActive" => $subActive
])
@section("fiche")

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
                                        <svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a.75.75 0 000 1.5h.253a.25.25 0 01.244.304l-.459 2.066A1.75 1.75 0 0010.747 15H11a.75.75 0 000-1.5h-.253a.25.25 0 01-.244-.304l.459-2.066A1.75 1.75 0 009.253 9H9z" clip-rule="evenodd" />
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
                            <input type="text" id="label" value="{{ $prestation->label }}" disabled
                                   class="bg-slate-100 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                        </div>
                    </div>
                    <div class="sm:col-span-2"></div>
                    <div class="sm:col-span-1"></div>
                    <div class="sm:col-span-1">
                        <label for="prixfas" class="block text-sm font-medium leading-6 text-gray-900">Prix FAS</label>
                        <div class="mt-2">
                            <input type="text" name="prixfas" id="prixfas"
                                   value="{{ $prestation->prixFraisInstalation	 }}"
                                   class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                        </div>
                    </div>
                    <div class="sm:col-span-1">
                        <label for="prixbrut" class="block text-sm font-medium leading-6 text-gray-900">Prix
                            brut</label>
                        <div class="mt-2">
                            <input type="text" name="prixbrut" id="prixbrut" value="{{ $prestation->prixBrut }}"
                                   class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                        </div>
                    </div>
                    <div class="sm:col-span-1">
                        <label for="prixmensuel" class="block text-sm font-medium leading-6 text-gray-900">Prix
                            mensuel</label>
                        <div class="mt-2">
                            <input type="text" name="prixmensuel" id="prixmensuel"
                                   value="{{ $prestation->prixMensuel }}"
                                   class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                        </div>
                    </div>
                    <div class="sm:col-span-2">

                    </div>
                    <div class="sm:col-span-1">
                        <label for="qte" class="block text-sm font-medium leading-6 text-gray-900">Quantité</label>
                        <div class="mt-2">
                            <input type="text" name="qte" id="qte" value="0"
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
            <a href="../add" type="button" class="text-sm font-semibold leading-6 text-gray-900">Retour</a>
            <button type="submit"
                    class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                Ajouter
            </button>
        </div>
    </form>


    <script>
        jQuery(document).ready(function () {
            var $ = jQuery;

            $("#qte").on("input", function () {
                calPrices();
            });
            $("#prixfas").on("input", function () {
                calPrices();
            });
            $("#prixbrut").on("input", function () {
                calPrices();
            });
            $("#prixmensuel").on("input", function () {
                calPrices();
            });

            function calPrices() {
                var qteValue = $("#qte").val();
                var totalfas = qteValue * $("#prixfas").val();
                var totalbrut = qteValue * $("#prixbrut").val();
                var totalmensuel = qteValue * $("#prixmensuel").val();
                $("#totalfas").val(totalfas);
                $("#totalbrut").val(totalbrut);
                $("#totalmensuel").val(totalmensuel);
                $("#total").val(totalfas + totalbrut + totalmensuel);
            }
        });
    </script>

@endsection

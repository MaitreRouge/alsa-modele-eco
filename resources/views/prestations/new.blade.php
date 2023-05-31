@extends("layouts.prestations",
[
    "pageTitle" => "Fiche Entreprise - Création d'une prestation",
    "subActive" => $subActive + 1
])
@section("fiche")

    <form method="POST" autocomplete="off">

        @csrf

        <div class="space-y-12">

            <div class="grid grid-cols-1 gap-x-8 gap-y-10 border-b border-gray-900/10 pb-12 md:grid-cols-3">
                <div>
                    <h2 class="text-base font-semibold leading-7 text-gray-900">Information de la prestation</h2>
                    <p class="mt-1 text-sm leading-6 text-gray-600">Sur cet écran il est possible de modifier
                        l'entièretée d'une prestation. Attention, chaque modification, une nouvelle version sera créée
                        et n'impactera pas les nouveaux devis !</p>
                </div>

                <div class="grid max-w-2xl grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6 md:col-span-2">

                    <div class="sm:col-span-3">
                        <div class="flex justify-between">
                            <label for="label" class="block text-sm font-medium leading-6 text-gray-900">Label</label>
                            <span id="labellength" class="text-sm leading-6 text-gray-500">0 / 100</span>
                        </div>
                        <div class="mt-2">
                            <input type="text" name="label" id="label"
                                   class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                        </div>
                    </div>


                    <div class="sm:col-span-3">
                        <label for="parent"
                               class="block text-sm font-medium leading-6 text-gray-900">Parent</label>
                        <div class="mt-2">
                            <select id="parent" name="parent"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:max-w-xs sm:text-sm sm:leading-6">
                                @foreach($categories as $c)
                                    <option value="{{ $c->id }}">{{ $c->label }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="sm:col-span-2 sm:col-start-1">
                        <label for="prixbrut" class="block text-sm font-medium leading-6 text-gray-900">Prix
                            brut</label>
                        <div class="mt-2">
                            <input type="text" name="prixbrut" id="prixbrut"
                                   class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                        </div>
                    </div>

                    <div class="sm:col-span-2">
                        <label for="prixFAS" class="block text-sm font-medium leading-6 text-gray-900">Prix FAS</label>
                        <div class="mt-2">
                            <input type="text" name="prixFAS" id="prixFAS"
                                   class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                        </div>
                    </div>

                    <div class="sm:col-span-2">
                        <label for="prixmensuel" class="block text-sm font-medium leading-6 text-gray-900">Prix
                            Mensuel</label>
                        <div class="mt-2">
                            <input type="text" name="prixmensuel" id="prixmensuel"
                                   class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                        </div>
                    </div>

                    <div class="sm:col-span-6">
                        <label for="note" class="block text-sm font-medium leading-6 text-gray-900">Note sur la
                            prestation</label>
                        <div class="mt-2">
                            <textarea rows="4" name="note" id="note"
                                      class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"></textarea>
                        </div>
                    </div>

                    <div class="sm:col-span-4">
                        <div class="items-center justify-between">
                            <span class="flex flex-grow flex-col">
                                <span class="text-sm font-medium leading-6 text-gray-900">Prix de vente</span>
                                <span class="text-sm text-gray-500">Est ce que la prestation necessite un prix de vente ?</span>
                            </span>
                        </div>
                    </div>
                    <div class="sm:col-span-2 my-auto">
                        <input type="checkbox" name="prixVente"
                               class="my-auto bg-gray-200 relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:ring-offset-2"
                               role="switch"/>
                    </div>

                </div>
            </div>
        </div>

        <div class="mt-6 flex items-center justify-end gap-x-6">
            <button type="button" class="text-sm font-semibold leading-6 text-gray-900">Annuler</button>
            <button type="submit"
                    class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                Sauvegarder
            </button>
        </div>

    </form>


    <script>
        jQuery(document).ready(function () {
            var $ = jQuery;

            $("#label").on("input", function () {
                $("#labellength").text($("#label").val().length + " / 100")
            });


            $("#labellength").text($("#label").val().length + " / 100")
        });
    </script>

@endsection

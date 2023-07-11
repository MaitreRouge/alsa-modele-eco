@extends("layouts.prestations",
[
    "pageTitle" => "Fiche Entreprise - Edition d'une prestation",
    "subActive" => $subActive + 1
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
                            <h3 class="text-base font-semibold leading-6 text-gray-900" id="modal-title">Supprimer la catégorie ?</h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">
                                    La suppression de la catégorie est irréversible et supprimera toutes les prestations dépendant ce la catégorie.</p>
                            </div>
                        </div>
                    </div>
                    <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
                        <a href="/" type="button"
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

            <div class="grid grid-cols-1 gap-x-8 gap-y-10 border-b border-gray-900/10 pb-12 md:grid-cols-3">
                <div>
                    <h2 class="text-base font-semibold leading-7 text-gray-900">Information de la catégorie</h2>
                    <p class="mt-1 text-sm leading-6 text-gray-600">Sur cet écran il est possible de modifier
                        le nom et le parent d'une cétagorie. Chaque modification est silencieuse et n'entrainera pas la création d'un historique</p>
                </div>

                <div class="grid max-w-2xl grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6 md:col-span-2">

                    <div class="sm:col-span-4">
                        <div class="flex justify-between">
                            <label for="label" class="block text-sm font-medium leading-6 text-gray-900">Label</label>
                            <span id="labellength" class="text-sm leading-6 text-gray-500">0 / 100</span>
                        </div>
                        <div class="mt-2">
                            <input type="text" name="label" id="label" value="{{ $category->label??null }}"
                                   class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                        </div>
                    </div>

                    <div class="sm:col-span-2">
                        <label for="parent" class="block text-sm font-medium leading-6 text-gray-900">Parent</label>
                        <select id="parent" name="parent" class="mt-2 block w-full rounded-md border-0 py-1.5 pl-3 pr-10 text-gray-900 ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-indigo-600 sm:text-sm sm:leading-6">
                            @foreach($parents as $p)
                                <option value="{{ $p->id }}" {{(($category->parentID??0) === $p->id)?"selected":""}}>{{$p->label}}</option>
                            @endforeach
                        </select>
                    </div>


                    <div class="sm:col-span-6">
                        <label for="note" class="block text-sm font-medium leading-6 text-gray-900">Note sur la
                            catégorie</label>
                        <div class="mt-2">
                            <textarea rows="4" name="note" id="note"
                                      class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">{{ $category->note??"" }}</textarea>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div class="mt-6 flex items-center justify-end gap-x-6">
            <button type="button" class="text-sm font-semibold leading-6 text-gray-900">Annuler</button>
            <button id="button_ras" type="button" class="rounded-md bg-red-50 px-3 py-2 text-sm font-semibold text-red-600 shadow-sm hover:bg-red-100">Supprimer la catégorie</button>
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

            $("#button_ras").click(function () {
                $("#error-message").show()
            });

            $("#delete-back").click(function () {
                $("#error-message").hide();
            });
        });


    </script>

@endsection

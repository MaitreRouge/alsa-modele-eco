@php
    use App\Models\User;
    $commerciaux = User::all();
@endphp
@extends("layouts.fiche",
[
    "pageTitle" => "Fiche Entreprise",
    "subActive" => 1
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
                                deivs ?</h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">
                                    Je sais qu'il est facile de glisser sur le bouton supprimer ou de se tromper mais
                                    est-tu sûr de vouloir supprimer tout ce travail ?</p>
                            </div>
                        </div>
                    </div>
                    <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
                        <a href="/delete/{{ $client->id }}" type="button"
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
            <div class="grid grid-cols-1 gap-x-8 gap-y-10 border-b border-gray-900/10 pb-12 md:grid-cols-3">
                <div>
                    <h2 class="text-base font-semibold leading-7 text-gray-900">Informations administatives et
                        techniques</h2>
                    <p class="mt-1 text-sm leading-6 text-gray-600">Ces informations sont necessaires pour la création
                        d'un devis !</p>
                </div>

                <div class="grid max-w-2xl grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6 md:col-span-2">
                    <div class="sm:col-span-3">
                        <label for="raison-sociale" class="block text-sm font-medium leading-6 text-gray-900">Raison
                            sociale</label>
                        <div class="mt-2">
                            <input type="text" name="raison-sociale" id="raison-sociale"
                                   value="{{ $client->raisonSociale }}"
                                   class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                        </div>
                    </div>
                    <div class="sm:col-span-3">
                        <label for="name" class="block text-sm font-medium leading-6 text-gray-900">Nom du
                            client</label>
                        <div class="mt-2">
                            <input type="text" name="name" id="name" value="{{ $client->nom }}"
                                   class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                        </div>
                    </div>
                    <div class="sm:col-span-3">
                        <label for="rpap" class="block text-sm font-medium leading-6 text-gray-900">R-PAP</label>
                        <div class="mt-2">
                            <input type="number" name="rpap" id="rpap" value="{{ $client->RPAP }}"
                                   class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                        </div>
                    </div>
                    <div class="sm:col-span-3">
                        <label for="date-signature" class="block text-sm font-medium leading-6 text-gray-900">Date
                            signature</label>
                        <div class="mt-2">
                            <input type="date" name="date-signature" id="date-signature"
                                   value="{{ $client->dateSignature }}"
                                   class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                        </div>
                    </div>
                    <div class="sm:col-span-3">
                        <label for="date-premiere-connxion" class="block text-sm font-medium leading-6 text-gray-900">Date
                            première connexion</label>
                        <div class="mt-2">
                            <input type="date" name="date-premiere-connxion" id="date-premiere-connxion"
                                   value="{{ $client->datePremiereConnexion }}"
                                   class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                        </div>
                    </div>
                    <div class="sm:col-span-3">
                        <label for="commercial" class="block text-sm font-medium leading-6 text-gray-900">Commercial</label>
                        <div class="mt-2">
                            <select id="commercial" name="commercial"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:max-w-xs sm:text-sm sm:leading-6">
                                @foreach($commerciaux as $c)
                                    <option
                                        value="{{ $c->id }}" {{ ($c->id == $client->commercial)?"selected":"" }}>{{ $c->getPublicName() }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="sm:col-span-3">
                        <label for="agence" class="block text-sm font-medium leading-6 text-gray-900">Agence</label>
                        <div class="mt-2">
                            <select id="agence" name="agence"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:max-w-xs sm:text-sm sm:leading-6">
                                @foreach([2010,2020,2030,2040,2050,2090] as $agence)
                                    <option
                                        value="{{ $agence }}" {{ ($agence == $client->agence)?"selected":"" }}>{{ $agence }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="sm:col-span-3">
                        <label for="nb-sites" class="block text-sm font-medium leading-6 text-gray-900">Nombre de
                            sites</label>
                        <div class="mt-2">
                            <input type="number" name="nb-sites" id="nb-sites" value="{{ $client->nbSites }}"
                                   class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                        </div>
                    </div>
                    <div class="sm:col-span-3">
                        <label for="engagement"
                               class="block text-sm font-medium leading-6 text-gray-900">Engagement</label>
                        <div class="mt-2">
                            <input type="number" name="engagement" id="engagement" value="{{ $client->engagement }}"
                                   class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                        </div>
                    </div>


                    <div class="sm:col-span-4">
                        <div class="items-center justify-between">
                                    <span class="flex flex-grow flex-col">
                                        <span class="text-sm font-medium leading-6 text-gray-900"
                                              id="upgrade">Upgrade</span>
                                        <span class="text-sm text-gray-500" id="upgrade">Est ce que le client possède déjà des services qui seront upgrade avec ce devis ?</span>
                                    </span>
                        </div>
                    </div>
                    <div class="sm:col-span-2 my-auto">
                        <input type="checkbox" name="upgrade"
                               class="my-auto bg-gray-200 relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:ring-offset-2"
                               role="switch" aria-checked="false" aria-labelledby="availability-label"
                               {{ ($client->upgrade)?"checked":"" }}
                               aria-describedby="availability-description">
                    </div>

                    <div class="sm:col-span-4">
                        <div class="items-center justify-between">
                                    <span class="flex flex-grow flex-col">
                                        <span class="text-sm font-medium leading-6 text-gray-900"
                                              id="availability-label">Nouveau site ?</span>
                                        <span class="text-sm text-gray-500" id="availability-description">Est ce que le devis concerne des nouveaus sites ou non ?</span>
                                    </span>
                        </div>
                    </div>


                    <div class="sm:col-span-2 my-auto">
                        <input type="checkbox" name="nvSite" id="nvSite"
                               class="my-auto bg-gray-200 relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:ring-offset-2"
                               role="switch" aria-checked="false" aria-labelledby="availability-label"
                               {{ ($client->nvSite)?"checked":"" }}
                               aria-describedby="availability-description">
                    </div>

                    <div class="sm:col-span-3" id="nbNvSitesDiv">
                        <label for="nbNvSites" class="block text-sm font-medium leading-6 text-gray-900">Nombre de nouveaux sites</label>
                        <div class="mt-2">
                            <input type="number" name="nbNvSites" id="nbNvSites" value="{{ $client->nbNvSites }}"
                                   class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                        </div>
                    </div>

                    <div class="sm:col-span-4">
                        <div class="items-center justify-between">
                                    <span class="flex flex-grow flex-col">
                                        <span class="text-sm font-medium leading-6 text-gray-900"
                                              id="availability-label">Nouveau client ?</span>
                                    </span>
                        </div>
                    </div>
                    <div class="sm:col-span-2 my-auto">
                        <input type="checkbox" name="nvClient"
                               class="my-auto bg-gray-200 relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:ring-offset-2"
                               role="switch" aria-checked="false" aria-labelledby="availability-label"
                               {{ ($client->nvClient)?"checked":"" }}
                               aria-describedby="availability-description">
                    </div>

                </div>
            </div>

        </div>

        <div class="mt-6 flex items-center justify-end gap-x-6">
            <a type="button" id="delete"
               class="rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-600">
                Supprimer
            </a>
            <button type="submit"
                    class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                Save
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

            $('#nvSite').change(function () {
                showNbNvSitesInput();
            });

            showNbNvSitesInput();

            function showNbNvSitesInput () {
                if ($('#nvSite').is(":checked")) {
                    $('#nbNvSitesDiv').show()
                    $('#nbNvSites').enable()
                    return;
                } else {
                    $('#nbNvSitesDiv').hide()
                    $('#nbNvSites').disable()
                }
            }
        });
    </script>

@endsection

@extends("layouts.layout",
[
    "pageTitle" => "Creation devis",
    "activate" => 0
])
@section("main")

    <form method="POST" autocomplete="off">
        @csrf
        <div class="space-y-12">
            <div class="grid grid-cols-1 gap-x-8 gap-y-10 border-b border-gray-900/10 pb-12 md:grid-cols-3">
                <div>
                    <h2 class="text-base font-semibold leading-7 text-gray-900">Informations administatives et techniques</h2>
                    <p class="mt-1 text-sm leading-6 text-gray-600">Ces informations sont necessaires pour la création d'un devis !</p>
                </div>

                <div class="grid max-w-2xl grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6 md:col-span-2">
                    <div class="sm:col-span-3">
                        <label for="raison-sociale" class="block text-sm font-medium leading-6 text-gray-900">Raison
                            sociale</label>
                        <div class="mt-2">
                            <input type="text" name="raison-sociale" id="raison-sociale" value=""
                                   class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                        </div>
                    </div>
                    <div class="sm:col-span-3">
                        <label for="name" class="block text-sm font-medium leading-6 text-gray-900">Nom du client</label>
                        <div class="mt-2">
                            <input type="text" name="name" id="name"
                                   class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                        </div>
                    </div>
                    <div class="sm:col-span-3">
                        <label for="rpap" class="block text-sm font-medium leading-6 text-gray-900">R-PAP</label>
                        <div class="mt-2">
                            <input type="number" name="rpap" id="rpap"
                                   class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                        </div>
                    </div>
                    <div class="sm:col-span-3">
                        <label for="date-signature" class="block text-sm font-medium leading-6 text-gray-900">Date
                            signature</label>
                        <div class="mt-2">
                            <input type="date" name="date-signature" id="date-signature"
                                   class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                        </div>
                    </div>
                    <div class="sm:col-span-3">
                        <label for="date-premiere-connxion" class="block text-sm font-medium leading-6 text-gray-900">Date
                            première connexion</label>
                        <div class="mt-2">
                            <input type="date" name="date-premiere-connxion" id="date-premiere-connxion"
                                   class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                        </div>
                    </div>
                    <div class="sm:col-span-3">
                        <label for="commercial"
                               class="block text-sm font-medium leading-6 text-gray-900">Commercial</label>
                        <div class="mt-2">
                            <input type="text" name="commercial" id="commercial"
                                   class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                        </div>
                    </div>
                    <div class="sm:col-span-3">
                        <label for="agence" class="block text-sm font-medium leading-6 text-gray-900">Agence</label>
                        <div class="mt-2">
                            <select id="agence" name="agence"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:max-w-xs sm:text-sm sm:leading-6">
                                @foreach([10,20,30,40,50,90] as $agence)
                                    <option value="20{{ $agence }}">20{{ $agence }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="sm:col-span-3">
                        <label for="nb-sites" class="block text-sm font-medium leading-6 text-gray-900">Nombre de
                            sites</label>
                        <div class="mt-2">
                            <input type="number" name="nb-sites" id="nb-sites"
                                   class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                        </div>
                    </div>
                    <div class="sm:col-span-3">
                        <label for="engagement" class="block text-sm font-medium leading-6 text-gray-900">Engagement</label>
                        <div class="mt-2">
                            <input type="number" name="engagement" id="engagement" placeholder="12"
                                   class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                        </div>
                    </div>


                        <div class="sm:col-span-4">
                            <div class="items-center justify-between">
                                    <span class="flex flex-grow flex-col">
                                        <span class="text-sm font-medium leading-6 text-gray-900" id="upgrade">Upgrade</span>
                                        <span class="text-sm text-gray-500" id="upgrade">Est ce que le client possède déjà des services qui seront upgrade avec ce devis ?</span>
                                    </span>
                            </div>
                        </div>
                        <div class="sm:col-span-2 my-auto">
                        <input type="checkbox" name="upgrade"
                                class="my-auto bg-gray-200 relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:ring-offset-2"
                                role="switch" aria-checked="false" aria-labelledby="availability-label"
                                aria-describedby="availability-description">
                    </div>

                        <div class="sm:col-span-4">
                            <div class="items-center justify-between">
                                    <span class="flex flex-grow flex-col">
                                        <span class="text-sm font-medium leading-6 text-gray-900" id="availability-label">Nouveau site ?</span>
                                        <span class="text-sm text-gray-500" id="availability-description">Est ce que le devis concerne des nouveaus sites ou non ?</span>
                                    </span>
                            </div>
                        </div>
                        <div class="sm:col-span-2 my-auto">
                        <input type="checkbox" name="nvSite"
                                class="my-auto bg-gray-200 relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:ring-offset-2"
                                role="switch" aria-checked="false" aria-labelledby="availability-label"
                                aria-describedby="availability-description">
                    </div>

                        <div class="sm:col-span-4">
                            <div class="items-center justify-between">
                                    <span class="flex flex-grow flex-col">
                                        <span class="text-sm font-medium leading-6 text-gray-900" id="availability-label">Nouveau client ?</span>
                                    </span>
                            </div>
                        </div>
                        <div class="sm:col-span-2 my-auto">
                        <input type="checkbox" name="nvClient"
                                class="my-auto bg-gray-200 relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:ring-offset-2"
                                role="switch" aria-checked="false" aria-labelledby="availability-label"
                                aria-describedby="availability-description">
                    </div>

                </div>
            </div>

        </div>

        <div class="mt-6 flex items-center justify-end gap-x-6">
            <a href="/dashboard" type="button" class="text-sm font-semibold leading-6 text-gray-900">Cancel</a>
            <button type="submit"
                    class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                Save
            </button>
        </div>
    </form>

@endsection

@php use App\Models\Categorie;use App\Models\Prestation; @endphp
@extends("layouts.fiche",
[
    "pageTitle" => "Fiche Entreprise - ".ucfirst($name),
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
                            <h3 class="text-base font-semibold leading-6 text-gray-900" id="modal-title">Supprimer toutes les prestations ?</h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">
                                    Vous êtes sur le point de supprimer <span class="font-bold">toutes les prestations</span> de <span class="font-bold">toutes les catégories</span> (data,téléphonie et services)<br><br>Êtes vous sur de vouloir faire tout supprimer ?</p>
                            </div>
                        </div>
                    </div>
                    <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
                        <a href="/delete/{{ $cid }}/devis" type="button"
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

    @if(count($prestations) == 0)
        <a type="button" href="{{ strtolower($name) }}/add"
           class="relative block w-full rounded-lg border-2 border-dashed border-gray-300 p-12 text-center hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48"
                 aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M8 14v20c0 4.418 7.163 8 16 8 1.381 0 2.721-.087 4-.252M8 14c0 4.418 7.163 8 16 8s16-3.582 16-8M8 14c0-4.418 7.163-8 16-8s16 3.582 16 8m0 0v14m0-4c0 4.418-7.163 8-16 8S8 28.418 8 24m32 10v6m0 0v6m0-6h6m-6 0h-6"/>
            </svg>
            <span
                class="mt-2 block text-sm font-semibold text-gray-900">Ajouter une prestation {{ strtolower($name) }}</span>
        </a>
    @else
        <div class="px-4 sm:px-6 lg:px-8">
            <div class="sm:flex sm:items-center">
                <div class="sm:flex-auto">
                    <h1 class="text-base font-semibold leading-6 text-gray-900">Prestations</h1>
                    <p class="mt-2 text-sm text-gray-700">Ici se trouve la liste de toutes les
                        prestations {{ strtolower($name) }} du client. Elles peuvent être modifiés et supprimés. Rien
                        n'est définitif !</p>
                </div>
                <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
                    <a type="button" href="{{ strtolower($name) }}/add"
                       class="block rounded-md bg-indigo-600 px-3 py-2 text-center text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                        Ajouter une prestation
                    </a>
                </div>
            </div>
            <div class="mt-8 flow-root">
                <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                        <table class="min-w-full divide-y divide-gray-300">
                            <thead>
                            <tr>
                                <th scope="col"
                                    class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900">Label
                                </th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Qte
                                </th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Frais
                                    initiaux
                                </th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">
                                    Redevance mensuelle
                                </th>
                                <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-0">
                                    <span class="sr-only">Edit</span>
                                </th>
                            </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                            @foreach($prestations as $line)
                                    <?php
                                    $p = (Prestation::where("id", $line->catalogueID)
                                        ->where("version", $line->version)
                                        ->get())[0];
                                    ?>
                                <tr class="{{ ($line->conflict)?"bg-red-50 hover:bg-red-100":"hover:bg-gray-50" }} ">
                                    <td class="whitespace-nowrap py-4 pr-4 text-sm font-medium pl-4 {{ ($line ->conflict)?"text-red-500":"text-gray-900" }}">
                                        @if(!empty($line->optLinked))
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" style="display: inline" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.75a.75.75 0 110-1.5.75.75 0 010 1.5zM12 12.75a.75.75 0 110-1.5.75.75 0 010 1.5zM12 18.75a.75.75 0 110-1.5.75.75 0 010 1.5z" />
                                            </svg>
                                        @endif
                                        {{ $p->label }}</td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $line->quantite }}</td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ ($line->prixFraisInstalation??$p->prixFraisInstalation) * $line->quantite }}</td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ (($line->prixMensuel??$p->prixMensuel) * $line->quantite) }}</td>
                                    <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-0">
                                        @if(empty($line->optLinked) and (!$line->conflict))
                                        <a href="{{strtolower($name) }}/edit/{{ $line->id }}" class="text-indigo-600 hover:text-indigo-900 mr-4">Edit<span
                                                class="sr-only"></span></a>
                                        @elseif($line->conflict)
                                            <a href="{{strtolower($name) }}/delete/{{ $line->id }}" class="text-red-600 hover:text-red-900 mr-4">Supprimer<span
                                                    class="sr-only"></span></a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            <!-- More people... -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <button id="button_ras" type="button" class="mt-6 rounded-md bg-red-50 px-2.5 py-1.5 text-sm font-semibold text-red-600 shadow-sm hover:bg-red-100">Remise à zéro</button>
        </div>

        <script>
            jQuery(document).ready(function () {
                var $ = jQuery;

                $("#button_ras").click(function () {
                    $("#error-message").show()
                });

                $("#delete-back").click(function () {
                    $("#error-message").hide();
                });

            });
        </script>

    @endif

@endsection

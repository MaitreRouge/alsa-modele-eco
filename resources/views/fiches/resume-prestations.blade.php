@php use App\Models\Categorie;use App\Models\Prestation; @endphp
@extends("layouts.fiche",
[
    "pageTitle" => "Fiche Entreprisee - ".ucfirst($name),
    "subActive" => $subActive
])
@section("fiche")

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
        {{--        @dump($prestations)--}}
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
                                    class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-0">Label
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
                                <tr>
                                    <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-0">
                                        {{ $p->label }}</td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $line->quantite }}</td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ ($line->prixFraisInstalation??$p->prixFraisInstalation) * $line->quantite }}</td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ (($line->prixMensuel??$p->prixMensuel) * $line->quantite) }}</td>
                                    <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-0">
                                        <a href="#" class="text-indigo-600 hover:text-indigo-900">Edit<span
                                                class="sr-only"></span></a>
                                    </td>
                                </tr>
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

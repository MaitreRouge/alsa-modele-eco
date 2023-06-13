<?php

use App\Models\Devis;

$activated = "text-gray-900 group relative min-w-0 flex-1 overflow-hidden bg-white py-4 px-4 text-center text-sm font-medium hover:bg-gray-50 focus:z-10";
$default = "text-gray-500 hover:text-gray-700 group relative min-w-0 flex-1 overflow-hidden bg-white py-4 px-4 text-center text-sm font-medium hover:bg-gray-50 focus:z-10";

$lineActivated = "bg-indigo-500 absolute inset-x-0 bottom-0 h-0.5";
$lineDefault = "bg-transparent absolute inset-x-0 bottom-0 h-0.5";

$alertIcon = '<svg style="display: inherit" class="h-6 w-6 text-red-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
              <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
            </svg>';

//Check conflicts, if presents show red on category button
$conflicts = Devis::where("conflict", 1)->get();
$categoriesWithConflicts = [];
if (!empty($conflicts) and count($conflicts) > 0) {
    foreach ($conflicts as $conflict) {
        $categoriesWithConflicts[] = $conflict->getPrestation()->mainCategory()->id;
    }
}
?>

@extends("layouts.layout",
[
    "pageTitle" => $pageTitle??"Sans titre",
    "activate" => 1
])


@section("main")
    <div class="mb-6">
        <div class="hidden sm:block">
            <nav class="isolate flex divide-x divide-gray-200 rounded-lg shadow" aria-label="Tabs">
                <!-- Current: "text-gray-900", Default: "text-gray-500 hover:text-gray-700" -->
                <a href="/edit/{{ $cid??$client->id }}/fiche" class="{{ ($subActive == 1)?$activated:$default }}">
                    <span>Fiche client</span>
                    <span aria-hidden="true" class="{{ ($subActive == 1)?$lineActivated:$lineDefault }}"></span>
                </a>
                <a href="/edit/{{ $cid??$client->id }}/data" class="{{ ($subActive == 2)?$activated:$default }} {{ (in_array(1, $categoriesWithConflicts)?"text-red-500 hover:text-red-600":"") }}">
                    <span><?= (in_array(1, $categoriesWithConflicts)?$alertIcon:"") ?> Data</span>
                    <span aria-hidden="true" class="{{ ($subActive == 2)?$lineActivated:$lineDefault }}"></span>
                </a>
                <a href="/edit/{{ $cid??$client->id }}/telephonie" class="{{ ($subActive == 3)?$activated:$default }} {{ (in_array(2, $categoriesWithConflicts)?"text-red-500 hover:text-red-600":"") }}">
                    <span><?= (in_array(2, $categoriesWithConflicts)?$alertIcon:"") ?> Telephonie</span>
                    <span aria-hidden="true" class="{{ ($subActive == 3)?$lineActivated:$lineDefault }}"></span>
                </a>
                <a href="/edit/{{ $cid??$client->id }}/services" class="{{ ($subActive == 4)?$activated:$default }} {{ (in_array(3, $categoriesWithConflicts)?"text-red-500 hover:text-red-600":"") }}">
                    <span><?= (in_array(3, $categoriesWithConflicts)?$alertIcon:"") ?> Services</span>
                    <span aria-hidden="true" class="{{ ($subActive == 4)?$lineActivated:$lineDefault }}"></span>
                </a>
                <a href="/edit/{{ $cid??$client->id }}/resume" class="{{ ($subActive == 5)?$activated:$default }}">
                    <span> Résumé</span>
                    <span aria-hidden="true" class="{{ ($subActive == 5)?$lineActivated:$lineDefault }}"></span>
                </a>
            </nav>
        </div>
    </div>

    @yield("fiche")

@endsection

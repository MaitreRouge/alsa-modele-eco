@extends("layouts.layout",
[
    "pageTitle" => $pageTitle??"Sans titre",
    "activate" => 0
])

<?php
$activated = "text-gray-900 rounded-l-lg group relative min-w-0 flex-1 overflow-hidden bg-white py-4 px-4 text-center text-sm font-medium hover:bg-gray-50 focus:z-10";
$default = "text-gray-500 hover:text-gray-700 group relative min-w-0 flex-1 overflow-hidden bg-white py-4 px-4 text-center text-sm font-medium hover:bg-gray-50 focus:z-10";

$lineActivated = "bg-indigo-500 absolute inset-x-0 bottom-0 h-0.5";
$lineDefault = "bg-transparent absolute inset-x-0 bottom-0 h-0.5";
?>

@section("main")
<div class="mb-6">
    <div class="hidden sm:block">
        <nav class="isolate flex divide-x divide-gray-200 rounded-lg shadow" aria-label="Tabs">
            <!-- Current: "text-gray-900", Default: "text-gray-500 hover:text-gray-700" -->
            <a href="/edit/{{ $client->id }}/fiche" class="{{ ($subActive == 1)?$activated:$default }}">
                <span>Fiche client</span>
                <span aria-hidden="true" class="{{ ($subActive == 1)?$lineActivated:$lineDefault }}"></span>
            </a>
            <a href="/edit/{{ $client->id }}/data" class="{{ ($subActive == 2)?$activated:$default }}">
                <span>Data</span>
                <span aria-hidden="true" class="{{ ($subActive == 2)?$lineActivated:$lineDefault }}"></span>
            </a>
            <a href="/edit/{{ $client->id }}/telephonie" class="{{ ($subActive == 3)?$activated:$default }}">
                <span>Telephonie</span>
                <span aria-hidden="true" class="{{ ($subActive == 3)?$lineActivated:$lineDefault }}"></span>
            </a>
            <a href="/edit/{{ $client->id }}/services"  class="{{ ($subActive == 4)?$activated:$default }}">
                <span>Services</span>
                <span aria-hidden="true" class="{{ ($subActive == 4)?$lineActivated:$lineDefault }}"></span>
            </a>
            <a href="/edit/{{ $client->id }}/resume"  class="{{ ($subActive == 5)?$activated:$default }}">
                <span>Résumé</span>
                <span aria-hidden="true" class="{{ ($subActive == 5)?$lineActivated:$lineDefault }}"></span>
            </a>
        </nav>
    </div>
</div>

@yield("fiche")

@endsection

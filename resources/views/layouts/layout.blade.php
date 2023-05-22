<!--
  This example requires updating your template:

  ```
  <html class="h-full bg-yellowy-100">
  <body class="h-full">
  ```
-->
<html class="h-full bg-gray-100">
<head>
    <link rel="stylesheet" type="text/css" href="/dist/output.css">
</head>
<body class="h-full">
<div class="min-h-full">
    <!--
  This example requires updating your template:

  ```
  <html class="h-full bg-gray-100">
  <body class="h-full">
  ```
-->
    <div class="min-h-full">
        <div class="bg-gray-800 pb-32">
            <nav class="bg-gray-800">
                <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                    <div class="border-b border-gray-700">
                        <div class="flex h-16 items-center justify-between px-4 sm:px-0">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <img class="h-8 w-8" src="https://tailwindui.com/img/logos/mark.svg?color=indigo&shade=500" alt="Your Company">
                                </div>
                                <div class="hidden md:block">
                                    <div class="ml-10 flex items-baseline space-x-4">
                                        <!-- Current: "bg-gray-900 text-white", Default: "text-gray-300 hover:bg-gray-700 hover:text-white" -->
                                        <a href="/dashboard" class="{{ ($activate == 1)?"bg-gray-900 text-white":"text-gray-300" }} hover:bg-gray-700 rounded-md px-3 py-2 text-sm font-medium">Dashboard</a>
                                        <a href="/prestations" class="{{ ($activate == 2)?"bg-gray-900 text-white":"text-gray-300" }} hover:bg-gray-700 hover:text-white rounded-md px-3 py-2 text-sm font-medium">Prestations</a>
                                        <a href="/changelog" class="{{ ($activate == 3)?"bg-gray-900 text-white":"text-gray-300" }} hover:bg-gray-700 hover:text-white rounded-md px-3 py-2 text-sm font-medium">Changelog</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </nav>
            <header class="py-10">
                <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <h1 class="text-3xl font-bold tracking-tight text-white">{{ $pageTitle??"SansTitre" }}</h1>
                </div>
            </header>
        </div>

        <main class="-mt-32">
            <div class="mx-auto max-w-7xl px-4 pb-12 sm:px-6 lg:px-8">
                <div class="rounded-lg bg-white px-5 py-6 shadow sm:px-6"> @yield("main") </div>
            </div>
        </main>
    </div>

</body>
</html>

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('JSON to CSV') }}
        </h2>
    </x-slot>
    <container class="mx-auto flex flex-col items-center justify-center">
        <section>
            <div class="mt-6">
                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Paste your JSON data into the 'JSON Input' field,<br /> click 'Send', the converted CSV data will appear in the 'CSV Output' field.</h2>
            </div>
            <form class="mt-6 ">
                @csrf
                <div>
                    <x-input-label for="json-input" value="{{ __('JSON Input') }}" />
                    <textarea id="json-input" name="json" rows="10" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"></textarea>
                </div>
                <div class="mb-4">
                    <button type="button" onclick="document.getElementById('json-input').value = ''; alert('Data from JSON Input cleared!');" class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded-md shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Clear
                    </button>
                    <button class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded-md shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Send
                    </button>
                </div>
                <div class="mt-6">
                    <x-input-label for="csv-output" value="{{ __('CSV Output') }}" />
                    <textarea id="csv-output" name="csv" rows="10" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"></textarea>
                </div>
                <div>
                    <button type="button" onclick="document.getElementById('csv-output').value = ''; alert('Data from CSV Output cleared!');" class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded-md shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Clear
                    </button>
                </div>
            </form>
        </section>
    </container>
</x-app-layout>

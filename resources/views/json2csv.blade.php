@vite('resources/js/json_functions.js')

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('JSON to CSV') }}
        </h2>
    </x-slot>
    <container class="mx-auto flex flex-col items-center justify-center" x-data="converter">
        <section>
            <div class="mt-6">
                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Paste your JSON data into the 'JSON Input' field,<br /> click 'Send', the converted CSV data will appear in the 'CSV Output' field.</h2>
            </div>
            <form class="mt-6" x-on:submit.prevent="uploadJson">
                @csrf
                <div>
                    <x-input-label for="json-input" value="{{ __('JSON Input') }}" />
                    <textarea id="json-input" x-model="jsonData" name="json" rows="10" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="Paste your JSON data here"></textarea>
                    <p x-show="error" class="error text-white" x-text="error"></p>
                    <p x-show="success" class="success text-white">Conversion successful!</p>
                </div>
                <div class="mt-2">
                    <button type="button" onclick="document.getElementById('json-input').value = ''; alert('Data from JSON Input cleared!');" class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded-md shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Clear
                    </button>
                    <button type="submit" class="inline-flex items-center mr-3 px-4 py-2 text-sm font-medium text-white bg-green-600 border border-transparent rounded-md shadow-sm hover:bg-gree-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                        Send
                    </button>
                </div>
                <div class="mt-6">
                    <x-input-label for="csv-output" value="{{ __('CSV Output') }}" />
                    <textarea x-model="csvData" id="csv-output" name="csv" rows="10" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="Your converted CSV data"></textarea>
                </div>
                <div class="mt-2">
                    <button type="button" onclick="document.getElementById('csv-output').value = ''; alert('Data from CSV Output cleared!');" class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded-md shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Clear
                    </button>
                    <button type="button" @click="copyToClipboard()" class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-blue-400 border border-transparent rounded-md shadow-sm hover:bg-blue-400 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Copy
                    </button>
                </div>
                <div class="mt-6 mb-6" x-show="tableData.length > 0">
                    <x-input-label for="csv-output" value="{{ __('CSV Table Preview') }}" />
                    <div id="table-preview" style="margin-top: 20px;">
                         <h3 class="text-white">Table Preview</h3>
                         <div style="overflow-x: auto;">
                             <table class="csv-table">
                                 <thead>
                                     <tr>
                                         <template x-for="header in tableHeaders" :key="header">
                                             <th x-text="header"></th>
                                         </template>
                                     </tr>
                                 </thead>
                                 <tbody>
                                     <template x-for="(row, index) in tableData" :key="index">
                                         <tr>
                                             <template x-for="(value, key) in row" :key="key">
                                                 <td x-text="value"></td>
                                             </template>
                                         </tr>
                                     </template>
                                 </tbody>
                             </table>
                         </div>
                     </div>
                     <div class="mt-2">
                        <button type="button" @click="downloadCSV()" class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-blue-300 border border-transparent rounded-md shadow-sm hover:bg-blue-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Download
                        </button>
                     </div>
                </div>
            </form>
        </section>
    </container>
</x-app-layout>

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Create a New Listing
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                @if($errors->any())
                    <div class="mb-4 text-red-600">
                        <ul>
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('listings.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf

                    <!-- Search Input -->
                    <div class="relative">
                        <label class="block font-medium text-sm text-gray-700">Search Pokémon Name</label>
                        <input type="text" id="card_search" class="border-gray-300 rounded-md shadow-sm w-full mt-1" placeholder="Type a name (e.g. Charizard)..." autocomplete="off">
                        
                        <!-- Dropdown Results Container -->
                        <div id="search_results" class="absolute z-10 w-full bg-white border border-gray-200 rounded-md shadow-lg mt-1 max-h-60 overflow-y-auto hidden">
                            <!-- JavaScript will inject results here -->
                        </div>
                    </div>

                    <div>
                        <label class="block font-medium text-sm text-gray-700 mt-4">Card ID (Auto-fills)</label>
                        <input type="text" id="card_id" name="tcgdex_id" value="{{ old('tcgdex_id') }}" class="border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 w-full mt-1" placeholder="e.g. swsh3-136" required>
                    </div>

                    <div>
                        <label class="block font-medium text-sm text-gray-700">Condition</label>
                        <select name="condition" class="border-gray-300 rounded-md shadow-sm w-full mt-1" required>
                            <option value="Mint">Mint</option>
                            <option value="Near Mint">Near Mint</option>
                            <option value="Lightly Played">Lightly Played</option>
                            <option value="Heavily Played">Heavily Played</option>
                            <option value="Damaged">Damaged</option>
                        </select>
                    </div>

                    <div>
                        <label class="block font-medium text-sm text-gray-700">Price ($)</label>
                        <input type="number" step="0.01" min="0" name="price" value="{{ old('price') }}" class="border-gray-300 rounded-md shadow-sm w-full mt-1" required>
                    </div>

                    <div>
                        <label class="block font-medium text-sm text-gray-700">Physical Card Photo</label>
                        <input type="file" name="photo" accept="image/*" class="mt-1 block w-full" required>
                    </div>

                    <div>
                        <label class="block font-medium text-sm text-gray-700">Description (Optional)</label>
                        <textarea name="description" class="border-gray-300 rounded-md shadow-sm w-full mt-1">{{ old('description') }}</textarea>
                    </div>

                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">List Card</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        const searchInput = document.getElementById('card_search');
        const resultsBox = document.getElementById('search_results');
        const cardIdInput = document.getElementById('card_id');
        let timeout = null;

        searchInput.addEventListener('input', function() {
            clearTimeout(timeout);
            const query = this.value;

            if (query.length < 3) {
                resultsBox.classList.add('hidden');
                return;
            }

            // Wait 500ms after the user stops typing before calling the API
            timeout = setTimeout(() => {
                fetch(`/api/search-cards?q=${query}`)
                    .then(response => response.json())
                    .then(data => {
                        resultsBox.innerHTML = '';
                        
                        if (data.length === 0) {
                            resultsBox.innerHTML = '<div class="p-3 text-sm text-gray-500">No cards found.</div>';
                        } else {
                            data.forEach(card => {
                                const div = document.createElement('div');
                                div.className = 'p-2 border-b hover:bg-gray-100 cursor-pointer flex items-center gap-3';
                                
                                // TCGdex provides the base image URL; append /low.png for the thumbnail
                                const imgHtml = card.image 
                                    ? `<img src="${card.image}/low.png" class="h-12 w-auto rounded shadow-sm">` 
                                    : `<div class="h-12 w-9 bg-gray-200 rounded"></div>`;

                                div.innerHTML = `
                                    ${imgHtml}
                                    <div>
                                        <p class="font-bold text-sm text-gray-800">${card.name}</p>
                                        <p class="text-xs text-gray-500">ID: ${card.id}</p>
                                    </div>
                                `;
                                
                                // When clicked, populate the inputs and hide the dropdown
                                div.addEventListener('click', () => {
                                    cardIdInput.value = card.id;
                                    searchInput.value = card.name;
                                    resultsBox.classList.add('hidden');
                                });
                                
                                resultsBox.appendChild(div);
                            });
                        }
                        
                        resultsBox.classList.remove('hidden');
                    });
            }, 500); 
        });

        // Hide dropdown if user clicks anywhere else on the page
        document.addEventListener('click', function(event) {
            if (!searchInput.contains(event.target) && !resultsBox.contains(event.target)) {
                resultsBox.classList.add('hidden');
            }
        });
    </script>
</x-app-layout>
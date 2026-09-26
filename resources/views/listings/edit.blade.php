<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Edit {{ $listing->card->name }} Listing
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

                <form action="{{ route('listings.update', $listing) }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block font-medium text-sm text-gray-700">Condition</label>
                        <select name="condition" class="border-gray-300 rounded-md shadow-sm w-full mt-1" required>
                            <option value="Mint" {{ $listing->condition == 'Mint' ? 'selected' : '' }}>Mint</option>
                            <option value="Near Mint" {{ $listing->condition == 'Near Mint' ? 'selected' : '' }}>Near Mint</option>
                            <option value="Lightly Played" {{ $listing->condition == 'Lightly Played' ? 'selected' : '' }}>Lightly Played</option>
                            <option value="Heavily Played" {{ $listing->condition == 'Heavily Played' ? 'selected' : '' }}>Heavily Played</option>
                            <option value="Damaged" {{ $listing->condition == 'Damaged' ? 'selected' : '' }}>Damaged</option>
                        </select>
                    </div>

                    <div>
                        <label class="block font-medium text-sm text-gray-700">Price ($)</label>
                        <input type="number" step="0.01" min="0" name="price" value="{{ old('price', $listing->price) }}" class="border-gray-300 rounded-md shadow-sm w-full mt-1" required>
                    </div>

                    <div>
                        <label class="block font-medium text-sm text-gray-700">Description (Optional)</label>
                        <textarea name="description" class="border-gray-300 rounded-md shadow-sm w-full mt-1">{{ old('description', $listing->description) }}</textarea>
                    </div>

                    <div class="flex gap-4">
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">Save Changes</button>
                        <a href="{{ route('listings.show', $listing) }}" class="bg-gray-200 text-gray-800 px-4 py-2 rounded-md hover:bg-gray-300">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
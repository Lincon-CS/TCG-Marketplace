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

                    <div>
                        <label class="block font-medium text-sm text-gray-700">TCGdex Card ID (e.g., swsh3-136)</label>
                        <input type="text" name="tcgdex_id" value="{{ old('tcgdex_id') }}" class="border-gray-300 rounded-md shadow-sm w-full mt-1" required>
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
</x-app-layout>
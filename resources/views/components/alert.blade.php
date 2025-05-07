@if (session('success'))
    <div id="success-alert" class="bg-green-100 text-green-800 px-4 py-2 rounded mb-4 relative transition-opacity duration-300 ease-in-out">
        <button onclick="closeAlert('success-alert')" class="absolute top-1 right-2 text-lg font-bold text-green-800 hover:text-green-600">&times;</button>
        {{ session('success') }}
    </div>
@endif

@if ($errors->any())
    <div id="error-alert" class="bg-red-100 text-red-800 px-4 py-2 rounded mb-4 relative transition-opacity duration-300 ease-in-out">
        <button onclick="closeAlert('error-alert')" class="absolute top-1 right-2 text-lg font-bold text-red-800 hover:text-red-600">&times;</button>
        @foreach ($errors->all() as $error)
            <div>{{ $error }}</div>
        @endforeach
    </div>
@endif


@if (session('success'))
    <div class="bg-green-100 text-green-800 px-4 py-2 rounded mb-4">
        {{ session('success') }}
    </div>
@endif

@if ($errors->any())
    <div class="bg-red-100 text-red-800 px-4 py-2 rounded mb-4">
        @foreach ($errors->all() as $error)
            <div>{{ $error }}</div>
        @endforeach
    </div>
@endif

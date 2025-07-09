<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat Latihan Baru</title>
    {{-- Anda bisa menautkan file CSS di sini, misalnya dari Bootstrap atau Tailwind --}}
    <style>
        body { font-family: sans-serif; line-height: 1.6; padding: 2em; }
        div { margin-bottom: 1em; }
        label { display: block; margin-bottom: 0.25em; }
        input, textarea, select, button { width: 300px; padding: 0.5em; }
        .error-list { color: #D8000C; background-color: #FFD2D2; padding: 1em; margin-bottom: 1em; border-radius: 5px; }
    </style>
</head>
<body>
    <h1>Buat Latihan Baru</h1>

    {{-- Bagian ini untuk menampilkan error validasi jika ada --}}
    @if ($errors->any())
        <div class="error-list">
            <strong>Oops! Ada beberapa masalah dengan input Anda:</strong>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- INI ADALAH FORM YANG AKAN MENGIRIM DATA --}}
    <form action="{{ route('exercises.store') }}" method="POST">
        @csrf

        <div>
            <label for="name">Nama Latihan:</label>
            <input type="text" id="name" name="name" value="{{ old('name') }}" required>
        </div>
        
        <div>
            <label for="description">Deskripsi:</label>
            <textarea id="description" name="description" rows="4" required>{{ old('description') }}</textarea>
        </div>
        
        <div>
            <label for="category_id">Kategori:</label>
            <select name="category_id" id="category_id" required>
                <option value="">-- Pilih Kategori --</option>
                {{-- Variabel $categories ini dikirim dari method `create()` di controller Anda --}}
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>
        
        <div>
            <button type="submit">Simpan Latihan</button>
        </div>
    </form>

</body>
</html>
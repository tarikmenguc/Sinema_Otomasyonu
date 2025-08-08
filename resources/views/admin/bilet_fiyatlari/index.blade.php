@extends('admin.layouts.app') {{-- Eğer farklı bir layout varsa burayı düzenle --}}

@section('content')
<div class="container mt-5">
    <h2 class="mb-4">Bilet Fiyatları</h2>

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>Üye Tipi</th>
                <th>Fiyat (₺)</th>
                <th>İşlem</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($fiyatlar as $fiyat)
                <tr>
                    <td>{{ $fiyat->uye_tipi }}</td>
                    <td>{{ $fiyat->fiyat }}</td>
                    <td>
                        <a href="{{ route('admin.bilet_fiyatlari.edit', $fiyat->id) }}" class="btn btn-sm btn-primary">Düzenle</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection

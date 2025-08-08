@extends('admin.layouts.app')

@section('content')
<div class="container mt-5">
    <h2 class="mb-4">Bilet Fiyatını Güncelle</h2>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.bilet_fiyatlari.update', $fiyat->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label">Üye Tipi</label>
                    <input type="text" name="uye_tipi" class="form-control" value="{{ $fiyat->uye_tipi }}" disabled>
                </div>

                <div class="mb-3">
                    <label class="form-label">Fiyat (₺)</label>
                    <input type="number" name="fiyat" class="form-control" value="{{ $fiyat->fiyat }}" required min="0">
                    @error('fiyat')
                        <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-success">Güncelle</button>
                <a href="{{ route('admin.bilet_fiyatlari.index') }}" class="btn btn-secondary">Geri Dön</a>
            </form>
        </div>
    </div>
</div>
@endsection

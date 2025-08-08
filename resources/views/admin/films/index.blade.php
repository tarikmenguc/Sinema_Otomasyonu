@extends('admin.layouts.app')
@section('title','Filmler')

@section('content')
  <div class="space-y-6">
    <h1 class="text-2xl font-bold">Filmler</h1>

    <form method="GET" action="{{ route('admin.films.index') }}" class="flex space-x-2 mb-4">
    <input type="text" name="title" value="{{ request('title') }}"
           class="px-3 py-2 border rounded shadow-sm focus:outline-none focus:ring"
           placeholder="Film başlığı ara... (OMDb)">
    <button type="submit"
            class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
        Ara
    </button>
    <a href="{{ route('admin.films.index') }}"
       class="px-4 py-2 bg-gray-400 text-white rounded hover:bg-gray-500">
        Temizle
    </a>
</form>


    <div class="overflow-x-auto bg-white shadow rounded p-4">
      <table id="films-table" class="min-w-full display">
        <thead class="bg-gray-50">
          <tr>
            <th>#</th>
            <th>Başlık</th>
            <th>Yıl</th>
            <th>IMDb ID</th>
            <th>Oluşturma</th>
            <th>İşlemler</th>
          </tr>
        </thead>
        <tbody></tbody>
      </table>
    </div>

  </div>
@endsection

@push('scripts')
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/jquery.dataTables.min.css">
  <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>

  <script>
  $(function () {
    $('#films-table').DataTable({
      processing: true,
      serverSide: true,
      ajax: "{{ route('admin.films.data') }}",
      order: [[0, 'desc']],
      columns: [
        { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable:false, searchable:false },
        { data: 'title',       name: 'title' },
        { data: 'year',        name: 'year' },
        { data: 'imdb_id',     name: 'imdb_id' },
        { data: 'created_at',  name: 'created_at' },
        { data: 'actions',     name: 'actions', orderable:false, searchable:false },
      ],
      pageLength: 10
    });
  });
  </script>
@endpush

@extends('admin.layouts.app')

@section('title', 'Kullanıcılar')

@section('content')
  <h1 class="text-2xl font-bold mb-4">Kullanıcılar</h1>

  @if(session('success'))
    <div class="mb-4 p-2 bg-green-200 text-green-800 rounded">
      {{ session('success') }}
    </div>
  @endif

  <table class="min-w-full bg-white">
    <thead class="bg-gray-50">
      <tr>
        <th class="px-4 py-2">#</th>
        <th class="px-4 py-2">Ad Soyad</th>
        <th class="px-4 py-2">E-posta</th>
        <th class="px-4 py-2">Roller</th>
        <th class="px-4 py-2">İşlem</th>
      </tr>
    </thead>
    <tbody class="divide-y divide-gray-200">
      @foreach($users as $user)
        <tr>
          <td class="px-4 py-2">{{ $user->id }}</td>
          <td class="px-4 py-2">{{ $user->name }}</td>
          <td class="px-4 py-2">{{ $user->email }}</td>
          <td class="px-4 py-2">
            @foreach($user->roles as $r)
              <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded text-xs">{{ $r->rol }}</span>
            @endforeach
          </td>
          <td class="px-4 py-2">
            <form action="{{ route('admin.users.toggleAdmin', $user) }}"
                  method="POST"
                  onsubmit="return confirm('Bu kullanıcının admin yetkisini değiştirilsin mi?');">
              @csrf
              <button
                class="px-3 py-1 text-xs rounded {{ $user->roles->contains($adminRole->id) ? 'bg-red-500 text-white' : 'bg-green-500 text-white' }}">
                {{ $user->roles->contains($adminRole->id) ? 'Adminlikten Al' : 'Admin Yap' }}
              </button>
            </form>
          </td>
        </tr>
      @endforeach
    </tbody>
  </table>
@endsection

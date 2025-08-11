{{-- resources/views/admin/users/index.blade.php --}}
@extends('admin.layouts.app')

@section('title', 'Kullanıcılar')

@section('content')
@php
  $toplam = $users instanceof \Illuminate\Pagination\AbstractPaginator ? $users->total() : $users->count();
  // $adminRole değişkeni Controller’dan geliyorsa:
  $adminRoleId = isset($adminRole) ? $adminRole->id : null;

  $adminSay = $users->filter(fn($u) => $adminRoleId ? $u->roles->contains($adminRoleId) : $u->roles->contains('rol', 'admin'))->count();
  $customerSay = $users->filter(fn($u) => $u->roles->contains('rol','customer'))->count();
@endphp

<div class="max-w-6xl mx-auto space-y-6">

  {{-- Başlık + özet --}}
  <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-indigo-700 to-violet-600 text-white shadow">
    <div class="p-6 md:p-8">
      <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-4">
        <div>
          <h1 class="text-2xl md:text-3xl font-bold tracking-tight">Kullanıcılar</h1>
          <p class="text-white/90 mt-1">Tüm kullanıcıları ve rollerini yönetin.</p>
          <div class="mt-4 grid grid-cols-3 gap-3 text-sm">
            <div class="rounded-xl bg-white/15 px-4 py-2">
              <div class="opacity-90">Toplam</div>
              <div class="text-2xl font-bold">{{ $toplam }}</div>
            </div>
            <div class="rounded-xl bg-white/15 px-4 py-2">
              <div class="opacity-90">Admin</div>
              <div class="text-2xl font-bold">{{ $adminSay }}</div>
            </div>
            <div class="rounded-xl bg-white/15 px-4 py-2">
              <div class="opacity-90">Customer</div>
              <div class="text-2xl font-bold">{{ $customerSay }}</div>
            </div>
          </div>
        </div>
        <a href="{{ route('admin.dashboard') }}"
           class="self-start md:self-auto inline-flex items-center gap-2 rounded-xl bg-white/15 px-4 py-2 hover:bg-white/25 transition">
          ← Panele Dön
        </a>
      </div>
    </div>
  </div>

  {{-- Flash --}}
  @if(session('success'))
    <div class="rounded-xl border border-green-200 bg-green-50 text-green-800 px-4 py-3">
      {{ session('success') }}
    </div>
  @endif
  @if(session('error'))
    <div class="rounded-xl border border-red-200 bg-red-50 text-red-700 px-4 py-3">
      {{ session('error') }}
    </div>
  @endif

  {{-- Filtre/Arama --}}
  <form method="GET" action="{{ route('admin.users.index') }}"
        class="bg-white rounded-2xl shadow p-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:gap-4">
    <div class="relative flex-1">
      <input type="text" name="q" value="{{ request('q') }}" placeholder="Ad, e-posta ile ara…"
             class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 pl-10">
      <svg xmlns="http://www.w3.org/2000/svg" class="absolute left-3 top-2.5 h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M12.9 14.32a8 8 0 111.414-1.414l3.387 3.387a1 1 0 01-1.414 1.414l-3.387-3.387zM14 8a6 6 0 11-12 0 6 6 0 0112 0z" clip-rule="evenodd" /></svg>
    </div>
    <div>
      <select name="role" class="rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500">
        <option value="">Tüm Roller</option>
        <option value="admin" @selected(request('role')==='admin')>Admin</option>
        <option value="customer" @selected(request('role')==='customer')>Customer</option>
      </select>
    </div>
    <div class="flex gap-2">
      <button class="rounded-xl bg-gray-900 text-white px-4 py-2 text-sm hover:bg-black transition">Uygula</button>
      <a href="{{ route('admin.users.index') }}"
         class="rounded-xl border border-gray-200 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition">Temizle</a>
    </div>
  </form>

  {{-- Masaüstü tablo --}}
  <div class="hidden md:block overflow-hidden bg-white shadow rounded-2xl border border-gray-200">
    <div class="overflow-x-auto">
      <table class="min-w-full">
        <thead class="bg-gray-50">
          <tr class="text-left text-xs font-medium text-gray-500 uppercase">
            <th class="px-5 py-3">#</th>
            <th class="px-5 py-3">Ad Soyad</th>
            <th class="px-5 py-3">E-posta</th>
            <th class="px-5 py-3">Roller</th>
            <th class="px-5 py-3 text-right">İşlem</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
          @forelse($users as $user)
            @php
              $isAdmin = $adminRoleId
                ? $user->roles->contains($adminRoleId)
                : $user->roles->contains('rol', 'admin');
            @endphp
            <tr class="hover:bg-gray-50 transition">
              <td class="px-5 py-3 text-gray-700 font-medium">{{ $user->id }}</td>
              <td class="px-5 py-3">
                <div class="font-semibold text-gray-900">{{ $user->name }}</div>
                <div class="text-xs text-gray-500">Kayıt: {{ optional($user->created_at)->format('d.m.Y') }}</div>
              </td>
              <td class="px-5 py-3 text-gray-700">{{ $user->email }}</td>
              <td class="px-5 py-3">
                <div class="flex flex-wrap gap-1">
                  @forelse($user->roles as $r)
                    @php
                      $badge = match($r->rol) {
                        'admin'    => 'bg-purple-100 text-purple-800',
                        'customer' => 'bg-blue-100 text-blue-800',
                        default    => 'bg-gray-100 text-gray-800',
                      };
                    @endphp
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $badge }}">{{ $r->rol }}</span>
                  @empty
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-gray-100 text-gray-800">—</span>
                  @endforelse
                </div>
              </td>
              <td class="px-5 py-3">
                <div class="flex items-center justify-end">
                  <form action="{{ route('admin.users.toggleAdmin', $user) }}" method="POST"
                        onsubmit="return confirm('Bu kullanıcının admin yetkisi değiştirilsin mi?');">
                    @csrf
                    <button class="px-3 py-1.5 rounded-xl text-xs font-semibold
                                   {{ $isAdmin ? 'bg-red-600 text-white hover:bg-red-700' : 'bg-emerald-600 text-white hover:bg-emerald-700' }} transition">
                      {{ $isAdmin ? 'Adminlikten Al' : 'Admin Yap' }}
                    </button>
                  </form>
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="5" class="px-5 py-10">
                <div class="text-center text-gray-500">
                  <p class="font-medium">Gösterilecek kullanıcı yok.</p>
                </div>
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  {{-- Mobil kartlar --}}
  <div class="md:hidden grid grid-cols-1 gap-4">
    @forelse($users as $user)
      @php
        $isAdmin = $adminRoleId
          ? $user->roles->contains($adminRoleId)
          : $user->roles->contains('rol', 'admin');
      @endphp
      <div class="bg-white rounded-2xl shadow p-4">
        <div class="flex items-start justify-between">
          <div>
            <div class="font-semibold text-gray-900">{{ $user->name }}</div>
            <div class="text-sm text-gray-700">{{ $user->email }}</div>
            <div class="flex flex-wrap gap-1 mt-2">
              @forelse($user->roles as $r)
                @php
                  $badge = match($r->rol) {
                    'admin'    => 'bg-purple-100 text-purple-800',
                    'customer' => 'bg-blue-100 text-blue-800',
                    default    => 'bg-gray-100 text-gray-800',
                  };
                @endphp
                <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $badge }}">{{ $r->rol }}</span>
              @empty
                <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-gray-100 text-gray-800">—</span>
              @endforelse
            </div>
          </div>
          <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-[11px] font-semibold
                       {{ $isAdmin ? 'bg-purple-100 text-purple-800' : 'bg-gray-100 text-gray-800' }}">
            {{ $isAdmin ? 'Admin' : 'Standart' }}
          </span>
        </div>

        <div class="mt-3">
          <form action="{{ route('admin.users.toggleAdmin', $user) }}" method="POST"
                onsubmit="return confirm('Bu kullanıcının admin yetkisi değiştirilsin mi?');">
            @csrf
            <button class="w-full text-center rounded-xl text-xs font-semibold
                           {{ $isAdmin ? 'bg-red-600 text-white hover:bg-red-700' : 'bg-emerald-600 text-white hover:bg-emerald-700' }} px-3 py-2 transition">
              {{ $isAdmin ? 'Adminlikten Al' : 'Admin Yap' }}
            </button>
          </form>
        </div>
      </div>
    @empty
      <div class="bg-white rounded-2xl shadow p-6 text-center text-gray-500">
        Gösterilecek kullanıcı yok.
      </div>
    @endforelse
  </div>

  {{-- (Varsa) sayfalama --}}
  @if(method_exists($users, 'links'))
    <div class="pt-2">{{ $users->appends(request()->query())->links() }}</div>
  @endif
</div>
@endsection

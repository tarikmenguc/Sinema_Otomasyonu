@auth
<nav class="bg-gray-800 text-white p-4">
  <div class="flex items-center space-x-4">
    {{-- Logo: Dashboard’a yönlendiren link’e alındı ve büyütüldü --}}
    <a href="{{ route('admin.dashboard') }}">
      <img src="{{ asset('images/logo.png') }}"
           alt="Logo"
           class="w-16 h-16 object-contain">
    </a>

    <ul class="flex space-x-6">
      <li><a href="{{ route('admin.films.index') }}"     class="hover:underline">Film İşlemleri</a></li>
      <li><a href="{{ route('admin.salon.index') }}"    class="hover:underline">Salon İşlemleri</a></li>
      <li><a href="{{ route('admin.seans.index') }}"    class="hover:underline">Seans İşlemleri</a></li>
      <li><a href="{{ route('admin.bilets.index') }}"   class="hover:underline">Bilet İşlemleri</a></li>
      <li><a href="{{ route('admin.users.index') }}"   class="hover:underline">Kullanıcı İşlemleri</a></li>
      <li>
    <a href="{{ route('admin.bilet_fiyatlari.index') }}">
        🎫 Bilet Fiyatları
             </a>
</li>   
    </ul>

    {{-- Çıkış butonu sağa dayandı --}}
    <form method="POST"
          action="{{ route('admin.logout') }}"
          class="ml-auto">
      @csrf
      <button type="submit"
              class="text-red-600 hover:underline">
        Çıkış Yap
      </button>
    </form>
  </div>
</nav>
@endauth

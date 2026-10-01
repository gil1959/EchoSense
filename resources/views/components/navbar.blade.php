<header class="bg-[#033067] sticky top-0 z-50 shadow-md">
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
<!-- Brand Identity -->
<a aria-label="EchoSense Dashboard" class="flex items-center space-x-3.5 group rounded-lg focus:outline-none" href="/dashboard">
<img src="https://lh3.googleusercontent.com/aida-public/AB6AXuCIEaDRbvfX8jj4PdyGI5ahez-NSPyrcEFCSGGByFrLjFBWWv_t-sWcoamyzlHaIT0XlJM-20hno2TKb6XTWkmSGRf9roDrAGE9LOUZWAZqZ5bVN4C9lE9KLd8AEONCvzKXKOQra4VaDMbs4x7kVF0iXjUzYdY2v55mz47j1QaydhuSY99AWlil5vwi0SgHTnm7DPyp7DaF3AgjCHwyV4MkHGkmfebpsnsTeqF9Cka8FfMTzdd18VRC2XUk5ZlvOw03Fw" alt="Logo EchoSense" class="w-10 h-10 object-contain bg-transparent">
<div>
<div class="flex items-center gap-2">
<span class="font-bold text-xl tracking-tight text-white font-sans">
              EchoSense
            </span>
<span aria-label="Status aktif" class="inline-block w-2.5 h-2.5 rounded-full bg-[#FEB161]" title="Status aktif"></span>
</div>
<p class="text-xs text-sky-100/80">Sonifikasi lingkungan aksesibel</p>
</div>
</a>
<!-- Navigation Links -->
<nav aria-label="Navigasi utama" class="hidden md:flex items-center space-x-1 lg:space-x-3">
<a class="px-4 py-2.5 rounded-lg text-sm font-semibold {{ request()->is('dashboard') || request()->is('/') ? 'text-white bg-white/10 relative' : 'text-white/80 hover:text-white hover:bg-white/10 transition-colors' }}" href="/dashboard" aria-label="EchoSense Dashboard">
          Dashboard
          @if(request()->is('dashboard') || request()->is('/'))
            <span aria-hidden="true" class="absolute bottom-1 left-4 right-4 h-0.5 bg-[#FEB161] rounded-full"></span>
          @endif
        </a>
<a class="px-4 py-2.5 rounded-lg text-sm font-semibold {{ request()->is('pengaturan') ? 'text-white bg-white/10 relative' : 'text-white/80 hover:text-white hover:bg-white/10 transition-colors' }}" href="/pengaturan">
          Pengaturan
          @if(request()->is('pengaturan'))
            <span aria-hidden="true" class="absolute bottom-1 left-4 right-4 h-0.5 bg-[#FEB161] rounded-full"></span>
          @endif
</a>
</nav>

<!-- Mobile Menu Button -->
<button class="md:hidden p-2 rounded-md text-white/80 hover:text-white hover:bg-white/10 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white transition" aria-controls="mobile-menu" aria-expanded="false" id="btn-mobile-menu">
  <span class="sr-only">Buka menu utama</span>
  <!-- Icon: Hamburger -->
  <svg class="block h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true" id="icon-menu-open">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
  </svg>
  <!-- Icon: Close -->
  <svg class="hidden h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true" id="icon-menu-close">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
  </svg>
</button>
</div>

<!-- Mobile Menu Panel (Hidden by default) -->
<div class="md:hidden hidden bg-[#02234d] border-t border-white/10" id="mobile-menu">
  <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
    <a href="/dashboard" class="block px-3 py-2 rounded-md text-base font-medium {{ request()->is('dashboard') || request()->is('/') ? 'text-white bg-white/10' : 'text-white/80 hover:text-white hover:bg-white/5' }}">Dashboard</a>
    <a href="/pengaturan" class="block px-3 py-2 rounded-md text-base font-medium {{ request()->is('pengaturan') ? 'text-white bg-white/10' : 'text-white/80 hover:text-white hover:bg-white/5' }}">Pengaturan</a>
  </div>
</div>

<script>
  document.getElementById('btn-mobile-menu')?.addEventListener('click', function() {
      const menu = document.getElementById('mobile-menu');
      const iconOpen = document.getElementById('icon-menu-open');
      const iconClose = document.getElementById('icon-menu-close');
      
      const isExpanded = this.getAttribute('aria-expanded') === 'true';
      this.setAttribute('aria-expanded', !isExpanded);
      
      menu.classList.toggle('hidden');
      iconOpen.classList.toggle('hidden');
      iconOpen.classList.toggle('block');
      iconClose.classList.toggle('hidden');
      iconClose.classList.toggle('block');
  });
</script>
</header>

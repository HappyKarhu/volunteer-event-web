<header class="bg-emerald-600 text-white p-4"x-data="{ open: false }">
  <div class="container mx-auto flex justify-between items-center">
    <h1 class="text-3xl font-semibold">
      <x-nav-link url="{{ url('/') }}" :active="request()->is('/')">
        Volunteerio
      </x-nav-link>
    </h1>
    <nav class="hidden md:flex items-center space-x-4">
      <x-nav-link :url="url('/events')" :active="request()->is('events')">All Events</x-nav-link>
      <x-nav-link :url="url('/events/saved')" :active="request()->is('events/saved')">Saved Events</x-nav-link>
      <x-nav-link :url="url('/login')" :active="request()->is('login')" icon="user">Login</x-nav-link>
      <x-nav-link :url="url('/register')" :active="request()->is('register')">Register</x-nav-link>
      <x-nav-link :url="route('events.create')" :active="request()->is('events/create')" icon="seedling">Create event</x-nav-link>
	
      <x-button-link :url="url('/dashboard')" :active="request()->is('dashboard')" icon="clipboard-user">My Dashboard</x-button-link>
    </nav>
    <button @click="open = !open"
                    
                    class="text-white md:hidden flex items-center"
                >
                    <i class="fa fa-bars text-2xl"></i>
                </button>
  </div>

  <!-- Mobile Menu -->
<nav
  x-show="open" @click.away="open = false"
  id="mobile-menu"
  class="md:hidden bg-emerald-600 text-white mt-5 pb-4 space-y-2"
	>
	  <x-nav-link :url="url('/events')" :active="request()->is('events')">All Events</x-nav-link>
	      <x-nav-link mobile="true" :url="url('/events/saved')" :active="request()->is('events/saved')">Saved Events</x-nav-link>
	      <x-nav-link mobile="true" :url="url('/login')" :active="request()->is('login')" icon="user">Login</x-nav-link>
	      <x-nav-link mobile="true" :url="url('/register')" :active="request()->is('register')">Register</x-nav-link>
	      <x-nav-link mobile="true" :url="route('events.create')" :active="request()->is('events/create')" icon="seedling">Create event</x-nav-link>
	
	      <x-button-link mobile="true" block="true" :url="url('/dashboard')" :active="request()->is('dashboard')" icon="clipboard-user">My Dashboard</x-button-link>
	    </nav>
</header>

<header class="bg-emerald-600 text-white p-4" x-data="{ open: false }">
    <div class="container mx-auto flex justify-between items-center">
        <h1 class="text-3xl font-semibold">
            <x-nav-link :url="url('/')" :active="request()->is('/')">
                Volunteerio
            </x-nav-link>
        </h1>

        <!--Public navigation links-->
        <nav class="hidden md:flex items-center space-x-4">
            <x-nav-link :url="route('events.index')" :active="request()->is('events')">
                All Events
            </x-nav-link>

            <!--Authenticated user links : dashboard is seen by organizers and volunteers -->
            @auth
                <!--Volunteer-specific links-->
                @if(auth()->user()->role === 'volunteer')
                <x-nav-link :url="route('events.saved')" :active="request()->is('events/saved')">
                    Saved Events
                </x-nav-link>
                @endif

                <!--Organizer-specific links-->
                @if(auth()->user()->role === 'organizer')
                    <x-nav-link :url="route('events.create')" :active="request()->is('events/create')" icon="seedling">
                        Create Event
                    </x-nav-link>
                @endif

                <x-button-link :url="route('dashboard')" :active="request()->is('dashboard')" icon="clipboard-user">
                  My Dashboard
                </x-button-link>

            @endauth

            <!--Public navigation links-->
            @guest
                <x-nav-link :url="url('/login')" :active="request()->is('login')" icon="user">
                    Login
                </x-nav-link>

                <x-nav-link :url="url('/register')" :active="request()->is('register')" icon="user-plus">
                    Register
                </x-nav-link>
            @endguest
        </nav>

        <button @click="open = !open" class="text-white md:hidden flex items-center">
            <i class="fa fa-bars text-2xl"></i>
        </button>
    </div>


  <!-- Mobile Menu -->
<nav
  x-show="open" @click.away="open = false"
  id="mobile-menu"
  class="md:hidden bg-emerald-600 text-white mt-5 pb-4 space-y-2"
	>
	  <nav x-show="open" @click.away="open = false" id="mobile-menu" class="md:hidden bg-emerald-600 text-white mt-5 pb-4 space-y-2">

      <!--Public navigation links-->
        <x-nav-link mobile="true" :url="route('events.index')" :active="request()->is('events')">
            All Events
        </x-nav-link>

        <!--Authenticated user links-->
        @auth

            <!--Volunteer-specific links-->
            @if(auth()->user()->role === 'volunteer')
            <x-nav-link mobile="true" :url="route('events.saved')" :active="request()->is('events/saved')">
                Saved Events
            </x-nav-link>
            @endif

            <!--Organizer-specific links-->
            @if(auth()->user()->role === 'organizer')
                <x-nav-link mobile="true" :url="route('events.create')" :active="request()->is('events/create')" icon="seedling">
                    Create Event
                </x-nav-link>
            @endif

            <x-button-link mobile="true" block="true" :url="route('dashboard')" :active="request()->is('dashboard')" icon="clipboard-user">
                My Dashboard
            </x-button-link>
  

        @endauth

        <!--Public navigation links-->
        @guest
            <x-nav-link mobile="true" :url="url('/login')" :active="request()->is('login')" icon="user">
                Login
            </x-nav-link>

            <x-nav-link mobile="true" :url="url('/register')" :active="request()->is('register')">
                Register
            </x-nav-link>
        @endguest
    </nav>
</header>

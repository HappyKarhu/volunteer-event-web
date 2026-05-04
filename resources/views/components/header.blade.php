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
                <!--Organizer-specific links-->
                @if(auth()->user()->role === 'organizer')
                    <x-nav-link :url="route('events.create')" :active="request()->is('events/create')" icon="seedling">
                        Create Event
                    </x-nav-link>
                @endif

                <!--bell buttton with dropdown notifications-->
            
                <div class="relative" x-data="{ notificationsOpen: false }">
                     @php
                        $unreadCount = auth()->user()->unreadNotifications->count();
                    @endphp
                    <button
                        type="button"
                        @click="notificationsOpen = ! notificationsOpen"
                        class="relative inline-flex h-10 w-10 items-center justify-center rounded-xl text-white transition
                            {{ $unreadCount > 0 
                                ? 'bg-emerald-500 hover:bg-amber-600' 
                                : 'bg-amber-500 hover:bg-amber-600' }}"
                                                aria-label="Notifications"
                                            >
                        <i class="fa fa-bell"></i>

                        @if(auth()->user()->unreadNotifications->count() > 0)
                            <span class="absolute -right-1 -top-1 inline-flex min-w-5 items-center justify-center rounded-full bg-amber-600 px-1.5 py-0.5 text-xs font-bold text-white">
                                {{ auth()->user()->unreadNotifications->count() }}
                            </span>
                        @endif
                    </button>

                    <div
                        x-show="notificationsOpen"
                        @click.away="notificationsOpen = false"
                        x-cloak
                        class="absolute right-0 z-50 mt-3 w-80 overflow-hidden rounded-xl bg-white text-gray-800 shadow-xl ring-1 ring-black/5"
                    >
                        <div class="flex items-center justify-between border-b border-gray-100 px-4 py-3">
                            <span class="text-sm font-semibold">Notifications</span>

                            @if(auth()->user()->unreadNotifications->count() > 0)
                                <form method="POST" action="{{ route('notifications.readAll') }}">
                                    @csrf
                                    <button type="submit" class="text-xs font-medium text-emerald-600 hover:underline">
                                        Mark all as read
                                    </button>
                                </form>
                            @endif
                        </div>

                        @forelse(auth()->user()->notifications()->latest()->limit(8)->get() as $notification)
                            <form method="POST" action="{{ route('notifications.read', $notification->id) }}">
                                @csrf
                                <button
                                    type="submit"
                                    class="block w-full px-4 py-3 text-left text-sm hover:bg-gray-100 {{ $notification->read_at ? 'text-gray-500' : 'bg-emerald-50 font-semibold text-gray-900' }}"
                                >
                                    <div>{{ $notification->data['title'] ?? 'Notification' }}</div>
                                    <div class="text-xs font-normal text-gray-500">
                                        {{ $notification->data['message'] ?? '' }}
                                    </div>
                                    <div class="mt-1 text-xs font-normal text-gray-400">
                                        {{ $notification->created_at->diffForHumans() }}
                                    </div>
                                </button>
                            </form>
                        @empty
                            <div class="px-4 py-3 text-sm text-gray-500">
                                No notifications yet.
                            </div>
                        @endforelse
                    </div>
                </div>

                <x-button-link :url="route('dashboard')" :active="request()->is('dashboard')" icon="clipboard-user">
                  My Dashboard
                </x-button-link>

                <form method="POST" action="{{ route('logout') }}" class="inline-flex">
                    @csrf
                    <button type="submit"
                        class="bg-amber-500 hover:bg-amber-600 text-white px-4 py-2 rounded-xl hover:shadow-md transition duration-300 inline-flex items-center">
                        <i class="fa fa-sign-out-alt mr-1"></i>
                        Log Out
                    </button>
                </form>

            @endauth

            <!--Public navigation links-->
            @guest
                <x-button-link :url="url('/login')" icon="user" bgClass="bg-emerald-50" hoverClass="hover:bg-emerald-100" textClass="text-emerald-700">
                    Login
                </x-button-link>

                <x-button-link :url="url('/register')" icon="user-plus">
                    Register
                </x-button-link>
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

            
            <!--Organizer-specific links-->
            @if(auth()->user()->role === 'organizer')
                <x-nav-link mobile="true" :url="route('events.create')" :active="request()->is('events/create')" icon="seedling">
                    Create Event
                </x-nav-link>
            @endif

            <x-button-link mobile="true" block="true" :url="route('dashboard')" :active="request()->is('dashboard')" icon="clipboard-user">
                My Dashboard
            </x-button-link>

            <div class="mx-4 rounded-xl bg-emerald-700 p-4" x-data="{ mobileNotificationsOpen: false }">
                <button
                    type="button"
                    @click="mobileNotificationsOpen = ! mobileNotificationsOpen"
                    class="flex w-full items-center justify-between text-left font-semibold text-white"
                >
                    <span>
                        <i class="fa fa-bell mr-2"></i>
                        Notifications
                    </span>

                    @if(auth()->user()->unreadNotifications->count() > 0)
                        <span class="rounded-full bg-red-600 px-2 py-1 text-xs">
                            {{ auth()->user()->unreadNotifications->count() }}
                        </span>
                    @endif
                </button>

                <div x-show="mobileNotificationsOpen" x-cloak class="mt-3 overflow-hidden rounded-xl bg-white text-gray-800">
                    @if(auth()->user()->unreadNotifications->count() > 0)
                        <form method="POST" action="{{ route('notifications.readAll') }}" class="border-b border-gray-100 px-4 py-3">
                            @csrf
                            <button type="submit" class="text-sm font-medium text-emerald-600">
                                Mark all as read
                            </button>
                        </form>
                    @endif

                    @forelse(auth()->user()->notifications()->latest()->limit(8)->get() as $notification)
                        <form method="POST" action="{{ route('notifications.read', $notification->id) }}">
                            @csrf
                            <button
                                type="submit"
                                class="block w-full px-4 py-3 text-left text-sm hover:bg-gray-100 {{ $notification->read_at ? 'text-gray-500' : 'bg-emerald-50 font-semibold text-gray-900' }}"
                            >
                                <div>{{ $notification->data['title'] ?? 'Notification' }}</div>
                                <div class="text-xs font-normal text-gray-500">
                                    {{ $notification->data['message'] ?? '' }}
                                </div>
                            </button>
                        </form>
                    @empty
                        <div class="px-4 py-3 text-sm text-gray-500">
                            No notifications yet.
                        </div>
                    @endforelse
                </div>
            </div>

            <form method="POST" action="{{ route('logout') }}" class="block">
                @csrf
                <button type="submit" class="bg-amber-500 hover:bg-amber-600 text-white px-4 py-2 rounded hover:shadow-md transition duration-300 block w-full text-left">
                    <i class="fa fa-sign-out-alt mr-1"></i>
                    Log Out
                </button>
            </form>
  

        @endauth

        <!--Public navigation links-->
        @guest
            <x-button-link mobile="true" block="true" :url="url('/login')" icon="user" bgClass="bg-emerald-50" hoverClass="hover:bg-emerald-100" textClass="text-emerald-700">
                Login
            </x-button-link>

            <x-button-link mobile="true" block="true" :url="url('/register')" icon="user-plus">
                Register
            </x-button-link>
        @endguest
    </nav>
</header>

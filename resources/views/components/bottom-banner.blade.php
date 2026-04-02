@props(['heading' => 'Want to make a difference?', 'subheading' => 'Find meaningful opportunities or create your own event and bring people together.'])
<section class="container mx-auto my-6">
    <div class="bg-emerald-600 text-white rounded p-4 flex items-center justify-between">
        <div>
            <h2 class="text-xl font-semibold">{{$heading}}</h2>
            <p class="text-white-200 text-lg mt-2">
                {{$subheading}}
            </p>
        </div>
        <x-button-link :url="url('/dashboard')" :active="request()->is('dashboard')" 
            icon="clipboard-user">
            My Dashboard
        </x-button-link>
    </div>
</section>
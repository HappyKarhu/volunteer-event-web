@props(['title' => 'Join or create opportunities to help your community'])

<section
  class="hero relative bg-cover bg-center bg-no-repeat py-12 md:py-20 flex items-center"
>
  <div class="overlay"></div>
  <div class="container mx-auto text-center z-10 px-4 md:px-0">
    <h2 class="text-3xl md:text-5xl text-white font-bold mb-8">{{ $title }}</h2>
    
      <a href="{{ route('events.index') }}"
        class="w-full md:w-auto bg-emerald-600 hover:bg-emerald-500 text-white hover:text-amber-500 px-4 py-3 rounded-lg shadow font-semibold transition-colors duration-200"
        >
        <i class="fa fa-calendar mr-1"></i> Browse All Events
      </a>
  </div>
</section>
@props(['title' => 'Join or create opportunities to help your community'])

<section
  class="hero relative bg-cover bg-center bg-no-repeat h-72 flex items-center"
>
  <div class="overlay"></div>
  <div class="container mx-auto text-center z-10">
    <h2 class="text-5xl text-white font-bold mb-8">{{ $title }}</h2>
    <form class="block mx-5 md:mx-auto md:space-x-2">
      <input
        type="text"
        name="keywords"
        placeholder="Keywords"
        class="w-full md:w-72 px-4 py-3 focus:outline-none"
      />
      <input
        type="text"
        name="location"
        placeholder="Location"
        class="w-full md:w-72 px-4 py-3 focus:outline-none"
      />
      <button
        class="w-full md:w-auto bg-emerald-600 hover:bg-emerald-500 text-white hover:text-amber-500 px-4 py-3 focus:outline-none transition-colors duration-200"
        >
        <i class="fa fa-search mr-1"></i> Search
      </button>
    </form>
  </div>
</section>
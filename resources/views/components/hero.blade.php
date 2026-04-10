@props(['title' => 'Join or create opportunities to help your community'])

<section
  class="hero relative bg-cover bg-center bg-no-repeat py-12 md:py-20 flex items-center"
>
  <div class="overlay"></div>
  <div class="container mx-auto text-center z-10 px-4 md:px-0">
    <h2 class="text-3xl md:text-5xl text-white font-bold mb-8">{{ $title }}</h2>
    <form class="block mx-5 md:mx-auto md:flex md:justify-center md:space-x-2">
      <x-inputs.text
        type="text"
        name="keywords"
        placeholder="Keywords"
        class="w-full md:w-72 px-4 py-3 mb-3 md:mb-0 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 shadow-sm focus:outline-none"
      />
      <x-inputs.text
        type="text"
        name="location"
        placeholder="Location"
        class="w-full md:w-72 px-4 py-3 mb-3 md:mb-0 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 shadow-sm focus:outline-none"
      />
      <button
        class="w-full md:w-auto bg-emerald-600 hover:bg-emerald-500 text-white hover:text-amber-500 px-4 py-3 rounded-lg shadow font-semibold transition-colors duration-200"
        >
        <i class="fa fa-search mr-1"></i> Search
      </button>
    </form>
  </div>
</section>
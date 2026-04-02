@props(['heading' => 'Welcome to Volunteerio', 'subheading' => 'Your platform for finding, joining, or creating volunteer events and opportunities in your community.'])

<section class="bg-emerald-600 text-white py-6 text-center">
    <div class="container mx-auto">
        <h2 class="text-3xl font-semibold">
            {{ $heading }}
        </h2>
        <p class="text-lg mt-2">
            {{ $subheading }}
        </p>
    </div>
</section>
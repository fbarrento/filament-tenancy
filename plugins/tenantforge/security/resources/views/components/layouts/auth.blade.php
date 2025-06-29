<div class="flex h-screen">
    <div class="flex justify-center w-full md:w-6/12 shrink-0">
        <div class="max-w-lg lg:max-w-xl px-4 w-full">

            <div class="my-10">
                <h1 class="text-2xl font-bold">{{ filament()->getBrandName() }}</h1>
            </div>

            <div class="my-16">
                <h2 class="text-lg font-semibold">{{ $heading ?? 'You must set the heading.' }}</h2>
                <p class="text-sm text-gray-600">
                    {{ $description ?? 'Experience the power of' . filament()->getBrandName() .'.' }}
                </p>
            </div>


            {{ $slot }}

        </div>

    </div>
    <div class="hidden md:flex w-full">
        vfvf
    </div>
</div>

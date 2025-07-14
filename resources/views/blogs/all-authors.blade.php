<x-app-layout>
    <h1 style="font-size: 50px;">Authors</h1>
    @foreach ($authors as $author)
        <div
            class="service-card w-[300px] shadow-xl cursor-pointer snap-start shrink-0 py-8 px-6 bg-white flex flex-col items-start gap-3 transition-all duration-300 group hover:bg-[#202127]">


            <p class="text-2xl font-bold group-hover:text-white text-black/80">
                {{ $author->name }}
            </p>
            <p class="text-sm text-gray-400">
                {{ $author->email }}
            </p>
            <p style="-webkit-text-stroke: 1px gray;
                                                                                              -webkit-text-fill-color: transparent;"
                class="self-end text-5xl font-bold">
                {{ $author->id }}
            </p>
        </div>
    @endforeach



</x-app-layout>
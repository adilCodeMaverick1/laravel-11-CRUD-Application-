<x-app-layout>
    <x-layout>
        <div class="container flex flex-wrap justify-center gap-6 px-4 py-8 mx-auto" id="blogs-container">
            {{-- Your existing blog cards --}}
            @foreach ($blogs as $blog)
                <div
                    class="w-full sm:w-[18em] md:w-[20em] lg:w-[22em] h-auto sm:h-[16em] border-2 border-[rgba(75,30,133,0.5)] rounded-[1.5em] bg-gradient-to-br from-[rgba(75,30,133,1)] to-[rgba(75,30,133,0.01)] text-white font-nunito p-[1em] flex flex-col justify-center items-left gap-[0.75em] backdrop-blur-[12px]">
                    <div>
                        <h1 class="text-[1.5em] sm:text-[2em] font-medium"> {{ $blog->title }}</h1>
                        <p class="text-[0.85em] sm:text-[0.95em] line-clamp-3">
                            {{ $blog->description }}
                        </p>
                        <span>Author: {{$blog->user->name}}</span>
                    </div>

                    <button
                        class="h-fit w-fit px-[1em] py-[0.25em] border-[1px] rounded-full flex justify-center items-center gap-[0.5em] overflow-hidden group hover:translate-y-[0.125em] duration-200 backdrop-blur-[12px] self-start">
                        <p>Contact</p>
                        <svg class="w-6 h-6 group-hover:translate-x-[10%] duration-300" stroke="currentColor"
                            stroke-width="1" viewBox="0 0 24 24" fill="white" xmlns="http://www.w3.org/2000/svg">
                            <path d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" stroke-linejoin="round" stroke-linecap="round">
                            </path>
                        </svg>
                    </button>
                </div>
            @endforeach
        </div>

        {{-- Loading indicator --}}
        <div id="loading-indicator" class="hidden py-4 text-center">
            <svg class="w-8 h-8 mx-auto text-white animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none"
                viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor"
                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                </path>
            </svg>
            <p class="mt-2 text-white">Loading more blogs...</p>
        </div>

        <script>
            let currentPage = {{ $blogs->currentPage() }};
            let lastPage = {{ $blogs->lastPage() }};
            let loading = false;

            const blogsContainer = document.getElementById('blogs-container');
            const loadingIndicator = document.getElementById('loading-indicator');

            window.addEventListener('scroll', () => {
                if (window.innerHeight + window.scrollY >= document.body.offsetHeight - 500 && !loading && currentPage < lastPage) {
                    loadMoreBlogs();
                }
            });

            function loadMoreBlogs() {
                loading = true;
                loadingIndicator.classList.remove('hidden'); // Show loading indicator

                currentPage++; // Increment page number for the next request
                const fetchUrl = `{{ route('blogs.loadMore') }}?page=${currentPage}`; // Use your named route for AJAX!

                console.log('Fetching URL:', fetchUrl); // Log the URL to verify

                fetch(fetchUrl, { // Use the corrected URL
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }
                        return response.json();
                    })
                    .then(data => {
                        data.data.forEach(blog => {
                            // Your existing blog card HTML
                            const blogCard = `
                            <div class="w-full sm:w-[18em] md:w-[20em] lg:w-[22em] h-auto sm:h-[16em] border-2 border-[rgba(75,30,133,0.5)] rounded-[1.5em] bg-gradient-to-br from-[rgba(75,30,133,1)] to-[rgba(75,30,133,0.01)] text-white font-nunito p-[1em] flex flex-col justify-center items-left gap-[0.75em] backdrop-blur-[12px]">
                                <div>
                                    <h1 class="text-[1.5em] sm:text-[2em] font-medium"> ${blog.title}</h1>
                                    <p class="text-[0.85em] sm:text-[0.95em] line-clamp-3">
                                        ${blog.description}
                                    </p>
                                    <span>Author: ${'soooo'}</span>
                                </div>

                                <button
                                    class="h-fit w-fit px-[1em] py-[0.25em] border-[1px] rounded-full flex justify-center items-center gap-[0.5em] overflow-hidden group hover:translate-y-[0.125em] duration-200 backdrop-blur-[12px] self-start">
                                    <p>Contact</p>
                                    <svg class="w-6 h-6 group-hover:translate-x-[10%] duration-300" stroke="currentColor"
                                        stroke-width="1" viewBox="0 0 24 24" fill="white" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" stroke-linejoin="round" stroke-linecap="round">
                                        </path>
                                    </svg>
                                </button>
                            </div>
                        `;
                            blogsContainer.insertAdjacentHTML('beforeend', blogCard);
                        });


                        loading = false;
                        loadingIndicator.classList.add('hidden');

                        if (currentPage >= lastPage) {
                            const noMoreBlogs = document.createElement('p');
                            noMoreBlogs.classList.add('text-center', 'text-white', 'py-4');
                            noMoreBlogs.textContent = 'No more blogs to load.';
                            blogsContainer.parentNode.appendChild(noMoreBlogs);
                        }
                    })
                    .catch(error => {
                        console.error('Error loading more blogs:', error);
                        loading = false;
                        loadingIndicator.classList.add('hidden');
                    });
            }
        </script>
    </x-layout>
</x-app-layout>
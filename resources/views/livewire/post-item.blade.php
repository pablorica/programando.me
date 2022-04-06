
<article
    tabindex="0" 
    aria-label="card {{ $post->id }}" 
    class="flex items-center shadow hover:bg-indigo-100 hover:shadow-lg hover:rounded transition duration-150 ease-in-out transform hover:scale-105 focus:outline-none mb-7 bg-white dark:bg-gray-800  p-6  rounded"
>
    <div>

        <div class="flex items-center border-b border-gray-200 dark:border-gray-700  pb-6">
            <a href="{{ route('post-detail', ['slug' => $post->post_slug]) }}">
            @if (isset($post->post_image))
                <img
                    src="/images/{{ $post->post_image }}" 
                    alt="{{ $post->post_title }}" 
                    class="w-12 h-12 rounded-full" 
                />
            @else                    
                <div class="w-12 h-12 bg-gray-300 dark:bg-gray-900  rounded-full flex flex-shrink-0"
                ></div>
            @endif
            </a>
                <div class="flex items-start justify-between w-full">
                    <div class="pl-3 w-full">
                        <a href="{{ route('post-detail', ['slug' => $post->post_slug]) }}">
                            <h2 
                                tabindex="0" 
                                class="focus:outline-none text-xl font-medium leading-5 text-gray-800 dark:text-white "
                            >{{ $post->post_title }}</h2>
                        </a>
                        <p 
                            tabindex="0" 
                            class="focus:outline-none text-sm leading-normal pt-2 text-gray-500 dark:text-gray-200 "
                        >
                            <a href="{{ route('post-category', ['category' => $post->post_category]) }}">{{ $post->post_category }}</a>
                        </p>
                    </div>
                    <div role="img" aria-label="bookmark">
                        <svg  class="focus:outline-none dark:text-white text-gray-800" width="28" height="28" viewBox="0 0 28 28" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M10.5001 4.66667H17.5001C18.1189 4.66667 18.7124 4.9125 19.15 5.35009C19.5876 5.78767 19.8334 6.38117 19.8334 7V23.3333L14.0001 19.8333L8.16675 23.3333V7C8.16675 6.38117 8.41258 5.78767 8.85017 5.35009C9.28775 4.9125 9.88124 4.66667 10.5001 4.66667Z" stroke="currentColor" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </div>
                </div>
        </div>
        <div class="px-2">
            <p 
                tabindex="0" 
                class="focus:outline-none text-sm leading-5 py-4 text-gray-600 dark:text-gray-200 "
            >
                <a href="{{ route('post-detail', ['slug' => $post->post_slug]) }}">
                    {{ $post->post_excerpt }}
                </a>
            </p>
            <div tabindex="0" class="focus:outline-none flex">
                <div class="py-2 px-4 text-xs leading-3 text-indigo-700 rounded-full bg-indigo-100">#tag1</div>
                <div class="py-2 px-4 ml-3 text-xs leading-3 text-indigo-700 rounded-full bg-indigo-100">#tag2</div>
            </div>
        </div>
    </div>

</article>
               
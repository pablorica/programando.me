<div class="min-h-full" >
    @include('livewire.navigation')
    {{-- @livewire('navigation') --}}
    

    <header class="bg-white shadow">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        @if (isset($category))
            <h1 class="text-3xl font-bold text-gray-900">Category: {{$category}}</h1>
        @else                    
            <h1 class="text-3xl font-bold text-gray-900">Dashboard</h1>
        @endif
        </div>
    </header>

    <main>
    @if ($posts)
        <div 
            class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8"
            x-init="$watch('showModal', value => console.log('showModal ' + value))"
        >
            <div class="w-full flex justify-center px-32 mb-5">
                <input wire:model="search" id="search" name="search" type="text" class="appearance-none block w-96 px-3 py-2 m-5 border border-gray-300 rounded-md placeholder-gray-400 focus:outline-none focus:shadow-outline-blue focus:border-blue-300 transition duration-150 ease-in-" placeholder="Search query ..." />
            </div>

            <div 
                aria-label="group of cards" 
                tabindex="0" 
                class="grid grid-cols-1 md:grid-cols-2 gap-4 focus:outline-none py-8 w-full"
            >
                @foreach ($posts as $post)
                    @livewire('post-item', ['post' => $post], key($post->id))
                @endforeach
            </div>

            {{ $posts->links() }}

            

        </div>
    @endif
    </main>

</div>
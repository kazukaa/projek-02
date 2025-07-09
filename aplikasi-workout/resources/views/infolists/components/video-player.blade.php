<x-dynamic-component :component="$getEntryWrapperView()" :entry="$entry">
    @php
        $url = $getState();
        // Ekstrak ID video dari URL YouTube
        preg_match('/(youtube\.com\/watch\?v=|youtu\.be\/)([a-zA-Z0-9_-]+)/', $url, $matches);
        $videoId = $matches[2] ?? null;
    @endphp

    @if ($videoId)
        <div class="aspect-w-16 aspect-h-9">
            <iframe 
                src="https://www.youtube.com/embed/{{ $videoId }}" 
                frameborder="0" 
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                allowfullscreen
                class="w-full h-full rounded-lg"
            ></iframe>
        </div>
    @else
        <p class="text-gray-500">Video tidak tersedia.</p>
    @endif
</x-dynamic-component>
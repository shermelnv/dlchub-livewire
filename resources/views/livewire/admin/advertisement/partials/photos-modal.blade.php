<flux:modal name="photos-modal" class="max-w-4xl w-auto">
    @if ($selectedPhotos)
        <div 
            x-data="{
                photos: @js($selectedPhotos),
                currentIndex: 0,
                get currentPhoto() { return this.photos[this.currentIndex]; },
                prev() {
                    this.currentIndex = (this.currentIndex === 0)
                        ? this.photos.length - 1
                        : this.currentIndex - 1;
                },
                next() {
                    this.currentIndex = (this.currentIndex === this.photos.length - 1)
                        ? 0
                        : this.currentIndex + 1;
                }
            }"
            class="relative flex items-center justify-center mt-10"
        >
            <!-- IMAGE -->
            <img 
                x-bind:src="`{{ Storage::url('') }}${currentPhoto}`"
                class="object-contain  max-h-[80vh] w-full  rounded-lg"
            >

            <!-- LEFT ARROW -->
            <button
                x-show="photos.length > 1"
                x-on:click="prev"
                class="absolute top-1/2 left-0 transform -translate-y-1/2 bg-black/50 text-white p-2 rounded-r"
            >
                ‹
            </button>

            <!-- RIGHT ARROW -->
            <button
                x-show="photos.length > 1"
                x-on:click="next"
                class="absolute top-1/2 right-0 transform -translate-y-1/2 bg-black/50 text-white p-2 rounded-l"
            >
                ›
            </button>

            <!-- DOTS -->
            <div class="absolute bottom-2 w-full flex justify-center gap-1">
                <template x-for="(photo, index) in photos" :key="index">
                    <div
                        class="w-2 h-2 rounded-full"
                        :class="index === currentIndex ? 'bg-white' : 'bg-gray-400/50'"
                    ></div>
                </template>
            </div>
        </div>
    @endif
</flux:modal>

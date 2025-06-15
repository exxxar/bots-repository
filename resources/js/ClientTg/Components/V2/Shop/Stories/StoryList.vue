<template>
    <div class="story-list-container d-flex overflow-auto p-3">
        <StoryItem
            v-for="(story, index) in sortedStories"
            :key="story.id"
            :story="story"
            :is-viewed="isStoryViewed(story.id)"
            @open-story="openStory(index)"
        />
    </div>

    <!-- Модальное окно -->
    <div
        v-if="currentStory !== null"
        class="modal fade show d-block"
        style="background-color: rgba(0, 0, 0, 0.9);"
        @click.self="closeStory"
    >
        <div class="modal-dialog modal-dialog-centered w-100 mw-100 m-0 h-100 mh-100">
            <div class="modal-content bg-transparent border-0 h-100 position-relative">
                <!-- Полоска времени -->
                <div class="progress position-absolute top-0 start-0 end-0" style="height: 4px; z-index: 10;">
                    <div
                        class="progress-bar bg-primary"
                        role="progressbar"
                        :style="{ width: `${progress}%` }"
                    ></div>
                </div>
                <!-- Контент истории -->
                <img
                    :src="sortedStories[currentStory].image"
                    class="w-100 h-100 object-fit-cover"
                    alt="Story"
                />
                <!-- Текстовая информация -->
                <div class="text-white p-3 bg-black bg-opacity-50 position-absolute bottom-0 start-0 end-0">
                    <h5 class="mb-1">{{ sortedStories[currentStory].title }}</h5>
                    <p class="mb-0">{{ sortedStories[currentStory].description }}</p>

                    <template v-if="sortedStories[currentStory].link">
                        <a :href="sortedStories[currentStory].link"
                           v-if="sortedStories[currentStory].link_type==='url'||sortedStories[currentStory].link_type==='bot'"
                           target="_blank" class="btn btn-primary rounded-5 w-100 p-3">
                            Перейти по ссылке
                        </a>

                        <a
                            href="javascript:void(0)"
                            @click="goToProductLink(sortedStories[currentStory].link)"
                            v-if="sortedStories[currentStory].link_type==='product'"
                            class="btn btn-primary rounded-5 w-100 p-3">
                            Открыть товар
                        </a>

                    </template>
                </div>
                <button
                    type="button"
                    class="btn-close btn-close-white position-absolute top-0 end-0 m-2"
                    style="z-index: 10;"
                    @click="closeStory"
                ></button>
            </div>
        </div>
    </div>
</template>

<script>
import StoryItem from './StoryItem.vue';

export default {
    name: 'StoryList',
    components: {
        StoryItem,
    },
    props: {
        stories: {
            type: Array,
            required: true,
        },
    },
    data() {
        return {
            currentStory: null,
            progress: 0,
            timer: null,
            viewedStories: JSON.parse(localStorage.getItem('viewedStories')) || [],
        };
    },
    computed: {
        sortedStories() {
            return [...this.stories].sort((a, b) => {
                const aViewed = this.viewedStories.includes(a.id);
                const bViewed = this.viewedStories.includes(b.id);
                if (aViewed && !bViewed) return 1;
                if (!aViewed && bViewed) return -1;
                return 0;
            });
        },
    },
    methods: {
        goToProductLink(url) {
            try {
                const urlObj = new URL(url);
                const startParam = urlObj.searchParams.get("start");
                const decoded = atob(startParam);

                // Пример: "001slug123product456"
                const slugMatch = decoded.match(/^001slug(\d+)product(\d+)$/);
                if (!slugMatch) {
                    this.$router.push({name: 'CatalogV2'})
                }

                const productId = slugMatch[2];

                this.$router.push({name: 'ProductV2', params: {productId: productId}})

            } catch (e) {
                this.$router.push({name: 'CatalogV2'})
            }
        },
        openStory(index) {
            this.currentStory = index;
            this.progress = 0;
            this.startTimer();
            this.markAsViewed(this.sortedStories[index].id);
        },
        closeStory() {
            this.currentStory = null;
            this.progress = 0;
            clearInterval(this.timer);
        },
        startTimer() {
            clearInterval(this.timer);
            this.timer = setInterval(() => {
                this.progress += 2;
                if (this.progress >= 100) {
                    this.nextStory();
                }
            }, 100); // 5 seconds total (100ms * 50 = 5000ms)
        },
        nextStory() {
            if (this.currentStory < this.sortedStories.length - 1) {
                // Переход к следующей истории
                this.currentStory++;
                this.progress = 0;
                this.markAsViewed(this.sortedStories[this.currentStory].id);
                this.startTimer();
            } else {
                // Закрытие модального окна, если это последняя история
                this.closeStory();
            }
        },
        markAsViewed(storyId) {
            if (!this.viewedStories.includes(storyId)) {
                this.viewedStories.push(storyId);
                localStorage.setItem('viewedStories', JSON.stringify(this.viewedStories));
            }
        },
        isStoryViewed(storyId) {
            return this.viewedStories.includes(storyId);
        },
    },
};
</script>

<style scoped>
.story-list-container {
    gap: 1rem;
}
</style>

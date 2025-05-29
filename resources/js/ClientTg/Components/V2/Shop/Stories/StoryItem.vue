<template>
    <div
        class="story-item flex-shrink-0 rounded-circle overflow-hidden cursor-pointer border"
        :class="{ 'border-primary': !isViewed, 'border-secondary': isViewed }"
        style="width: 80px; height: 80px;"
        @click="$emit('open-story')"
    >
        <img
            :src="story.thumbnail"
            class="w-100 h-100 object-fit-cover"
            :class="{ 'filter-grayscale': isViewed }"
            :alt="story.title"
        />
    </div>
</template>

<script>
export default {
    name: 'StoryItem',
    props: {
        story: {
            type: Object,
            required: true,
        },
        isViewed: {
            type: Boolean,
            default: false,
        },
    },
};
</script>
<style scoped lang="scss">
.story-item {
    box-shadow: 0px 0px 5px 0px;
}

.filter-grayscale {
    filter: grayscale(100%);
}
</style>

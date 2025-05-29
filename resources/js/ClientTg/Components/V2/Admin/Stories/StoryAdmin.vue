<script setup>
import Pagination from "@/ClientTg/Components/V1/Pagination.vue";
</script>
<template>
    <h2 class="mb-2">Истории</h2>

    <button
        type="button"
        class="btn btn-success p-3 w-100 mb-2"
        data-bs-toggle="modal"
        data-bs-target="#storyModal"
        @click="startCreateStory"
    >
        Создать новую историю
    </button>

    <!-- Модальное окно -->
    <div
        class="modal fade"
        id="storyModal"
        tabindex="-1"
        aria-labelledby="storyModalLabel"
        aria-hidden="true"
    >
        <div class="modal-dialog modal-dialog-centered modal-fullscreen">
            <form class="modal-content" @submit.prevent="saveStory">
                <div class="modal-header">
                    <h5 class="modal-title" id="storyModalLabel">
                        {{ isEditing ? 'Редактировать историю' : 'Создать историю' }}
                    </h5>
                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Закрыть"
                        @click="cancelForm"
                    ></button>
                </div>
                <div class="modal-body">

                    <!-- Заголовок -->
                    <div class="form-floating mb-2">
                        <input
                            v-model="formStory.title"
                            type="text"
                            class="form-control"
                            id="title"
                            placeholder="Введите заголовок"
                            required
                        />
                        <label for="title">Заголовок</label>
                    </div>

                    <!-- Миниатюра -->
                    <div class="mb-2">
                        <label class="form-label">Миниатюра</label>
                        <!--                        <div class="form-check form-switch mb-2">
                                                    <input
                                                        class="form-check-input"
                                                        type="checkbox"
                                                        id="thumbnailInputSwitch"
                                                        v-model="useThumbnailFile"
                                                    />
                                                    <label class="form-check-label" for="thumbnailInputSwitch">
                                                        {{ useThumbnailFile ? 'Загрузка файла' : 'Ввод ссылки' }}
                                                    </label>
                                                </div>-->

                        <div v-if="!useThumbnailFile" class="form-floating">
                            <input
                                v-model="formStory.thumbnail"
                                type="url"
                                class="form-control"
                                id="thumbnail"
                                placeholder="Введите URL миниатюры"
                                required
                            />
                            <label for="thumbnail">URL миниатюры</label>
                        </div>

                        <div v-else>
                            <input
                                type="file"
                                accept="image/*"
                                class="form-control"
                                @change="handleThumbnailUpload"
                                required
                            />
                        </div>
                    </div>

                    <!-- Изображение -->
                    <div class="mb-2">
                        <label class="form-label">Изображение истории</label>
                        <!--                        <div class="form-check form-switch mb-2">
                                                    <input
                                                        class="form-check-input"
                                                        type="checkbox"
                                                        id="imageInputSwitch"
                                                        v-model="useImageFile"
                                                    />
                                                    <label class="form-check-label" for="imageInputSwitch">
                                                        {{ useImageFile ? 'Загрузка файла' : 'Ввод ссылки' }}
                                                    </label>
                                                </div>-->

                        <div v-if="!useImageFile" class="form-floating">
                            <input
                                v-model="formStory.image"
                                type="url"
                                class="form-control"
                                id="image"
                                placeholder="Введите URL изображения"
                                required
                            />
                            <label for="image">URL изображения</label>
                        </div>

                        <div v-else>
                            <input
                                type="file"
                                accept="image/*"
                                class="form-control"
                                @change="handleImageUpload"
                                required
                            />
                        </div>
                    </div>

                    <!-- Описание -->
                    <div class="form-floating mb-2">
              <textarea
                  v-model="formStory.description"
                  class="form-control"
                  placeholder="Введите описание"
                  id="description"
                  style="height: 150px"
                  required
              ></textarea>
                        <label for="description">Описание</label>
                    </div>


                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary p-2 w-100">Сохранить</button>
                </div>
            </form>

        </div>
    </div>


    <!-- Карточки историй -->
    <div class="row row-cols-2 row-cols-md-3 g-4">
        <div v-for="story in stories" :key="story.id" class="col">
            <div class="card h-100">
                <div class="card-body text-center">
                    <div
                        class="story-item rounded-circle overflow-hidden cursor-pointer border mx-auto"
                        :class="{
              'border-primary': !isStoryViewed(story.id),
              'border-secondary': isStoryViewed(story.id),
            }"
                        style="width: 80px; height: 80px"
                        @click="viewStory(story.id)"
                    >
                        <img
                            :src="story.thumbnail"
                            class="w-100 h-100 object-fit-cover"
                            :class="{ 'filter-grayscale': isStoryViewed(story.id) }"
                            :alt="story.title"
                        />
                    </div>
                    <h5 class="card-title mt-3">{{ story.title }}</h5>
                    <p class="card-text">{{ story.description }}</p>
                </div>
                <div class="card-footer d-flex flex-column align-items-center">
                    <div class="d-flex mb-2">
                        <button
                            type="button"
                            class="btn btn-primary btn-sm me-2"
                            data-bs-toggle="modal"
                            data-bs-target="#storyModal"
                            @click="editStory(story)"
                        >
                            <i class="fa-solid fa-pen-to-square"></i>
                        </button>
                        <button
                            type="button"
                            class="btn btn-info btn-sm" @click="duplicateStory(story)">
                            <i class="fa-solid fa-clone"></i>
                        </button>
                    </div>
                    <button
                        type="button"
                        class="btn btn-link text-danger" @click="deleteStory(story.id)">Удалить
                    </button>

                </div>
            </div>
        </div>


    </div>

    <template v-if="stories_paginate_object">
        <div class="row" v-if="stories_paginate_object.meta.last_page>1">
            <div class="col-12">
                <Pagination
                    :simple="true"
                    v-on:pagination_page="nextStories"
                    v-if="stories_paginate_object"
                    :pagination="stories_paginate_object"/>
            </div>
        </div>
    </template>

</template>

<script>
export default {
    name: 'AdminPanel',

    data() {
        return {

            stories: [],
            stories_paginate_object: null,
            formStory: {
                title: null,
                thumbnail: null,
                image: null,
                description: null,
            },
            isEditing: false,
            useThumbnailFile: false,
            useImageFile: false,
            viewedStories: JSON.parse(localStorage.getItem('viewedStories')) || [],
        };
    },
    mounted() {
        this.loadStoriesList()
    },
    methods: {
        loadStoriesList(page = 1) {
            return this.$store.dispatch("loadStories", {
                page: page,
                size: 20
            }).then(() => {
                this.stories = this.$store.getters["getStories"]
                this.stories_paginate_object = this.$store.getters["getStoriesPaginateObject"]
            })
        },

        nextStories(index) {
            this.loadStoriesList(index)
        },
        startCreateStory() {
            this.formStory = {
                title: null,
                thumbnail: null,
                image: null,
                description: null,
            };
            this.isEditing = false;
            this.useThumbnailFile = false;
            this.useImageFile = false;
        },
        editStory(story) {
            this.formStory = {...story};
            this.isEditing = true;
            this.useThumbnailFile = false;
            this.useImageFile = false;
        },
        saveStory() {

            let data = new FormData();
            Object.keys(this.formStory)
                .forEach(key => {
                    const item = this.formStory[key] || ''
                    if (typeof item === 'object')
                        data.append(key, JSON.stringify(item))
                    else
                        data.append(key, item)
                });

            data.append("media_thumbnail", this.useThumbnailFile)
            data.append("media_image", this.useImageFile)

            this.$store.dispatch('saveStory', {
                storyForm: data
            })
                .then(() => {
                    this.startCreateStory()
                    this.closeModal();

                    this.$notify({
                        title: "Истории",
                        text: "История успешно сохранена",
                        type: "success",
                    });
                })
                .catch(() => {
                    this.$notify({
                        title: "Истории",
                        text: "Ошибка сохранения истории",
                        type: "error",
                    });
                })


        },
        cancelForm() {
            this.startCreateStory()
            this.closeModal();
        },
        handleThumbnailUpload(event) {
            const file = event.target.files[0];
            if (file && file.size <= 5 * 1024 * 1024) {
                this.formStory.thumbnail = file;
            } else {
                alert('Размер файла миниатюры не должен превышать 5 МБ');
                event.target.value = null;
            }
        },
        handleImageUpload(event) {
            const file = event.target.files[0];
            if (file && file.size <= 5 * 1024 * 1024) {
                this.formStory.image = file;
            } else {
                alert('Размер изображения не должен превышать 5 МБ');
                event.target.value = null;
            }
        },
        deleteStory(storyId) {
            this.$store.dispatch('deleteStory', {
                id: storyId
            }).then(() => {
                this.$notify({
                    title: "Истории",
                    text: "История успешно удалена",
                    type: "success",
                });

                this.loadStoriesList()
            }).catch(() => {
                this.$notify({
                    title: "Истории",
                    text: "Ошибка удаления истории",
                    type: "error",
                });
            })


        },
        duplicateStory(story) {
            const newStory = {
                ...story,
                id: Date.now(),
                title: `${story.title} (Copy)`,
            };
            this.$emit('create-story', newStory);
        },

        viewStory(storyId) {
            this.$emit('view-story', storyId);
        },
        isStoryViewed(storyId) {
            return this.viewedStories.includes(storyId);
        },
        closeModal() {
            const modal = document.getElementById('storyModal');
            const bootstrapModal = bootstrap.Modal.getInstance(modal);
            if (bootstrapModal) {
                bootstrapModal.hide();
            }
        },
    },
};
</script>

<style scoped>
.story-item {
    transition: border-color 0.3s;
}

.filter-grayscale {
    filter: grayscale(100%);
}
</style>

<script setup>
import Pagination from "@/ClientTg/Components/V1/Pagination.vue";
import StoryList from "@/ClientTg/Components/V2/Shop/Stories/StoryList.vue";
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
            <form class="modal-content"
                  id="story-form"
                  @submit.prevent="saveStory">
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

                        <template v-if="formStory.thumbnail">
                            <img
                                class="img-thumbnail w-100 mb-2"
                                v-lazy="getPhoto(formStory.thumbnail).imageUrl">
                        </template>

                        <!--                        <div v-if="!useThumbnailFile" class="form-floating">
                                                    <input
                                                        @change="onChangePhotos($event, formStory.thumbnail)"
                                                        type="url"
                                                        class="form-control"
                                                        id="thumbnail"
                                                        placeholder="Введите URL миниатюры"
                                                        required
                                                    />
                                                    <label for="thumbnail">URL миниатюры</label>
                                                </div>-->


                        <input
                            type="file"
                            accept="image/*"
                            class="form-control"
                            @change="handleThumbnailUpload($event, 'thumbnail')"
                            required
                        />

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

                        <template v-if="formStory.image">
                            test
                            <img
                                class="img-thumbnail w-100 mb-2"
                                v-lazy="getPhoto(formStory.image).imageUrl">

                        </template>

                        <!--                        <div v-if="!useImageFile" class="form-floating">
                                                    <input
                                                        @change="onChangePhotos($event, formStory.image)"
                                                        type="url"
                                                        class="form-control"
                                                        id="image"
                                                        placeholder="Введите URL изображения"
                                                        required
                                                    />
                                                    <label for="image">URL изображения</label>
                                                </div>-->


                        <input
                            type="file"
                            id="image"
                            accept="image/*"
                            class="form-control"
                            @change="handleThumbnailUpload($event, 'image')"
                            required
                        />

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

                    <!-- Ссылка -->
                    <div class="form-floating mb-2">
                        <input
                            v-model="formStory.link"
                            type="url"
                            class="form-control"
                            id="title"
                            placeholder="Введите заголовок"
                        />
                        <label for="title"><i class="fa-solid fa-link"></i> Ссылка</label>
                    </div>

                    <div class="form-floating mb-2" v-if="formStory.link">
                        <select
                            required
                            v-model="formStory.link_type"
                            class="form-select" id="link_type" aria-label="Floating label select example">
                            <option :value="item.key" v-for="item in link_types">{{ item.title }}</option>
                        </select>
                        <label for="link_type">Тип ссылки</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="form-check form-switch mb-2">
                        <input class="form-check-input"
                               type="checkbox"
                               v-model="formStory.need_auto_send_stories"
                               role="switch" id="script-settings-can_use_cash">
                        <label class="form-check-label" for="script-settings-can_use_cash">Автоматическая рассылка оповещения про новую историю: <span
                            v-bind:class="{'text-primary fw-bold':formStory.need_auto_send_stories}">вкл</span> \ <span
                            v-bind:class="{'text-primary fw-bold':!formStory.need_auto_send_stories}">выкл</span></label>
                    </div>
                    <button type="submit" class="btn btn-primary p-2 w-100">Сохранить</button>
                </div>
            </form>

        </div>
    </div>


    <template v-if="loadingStories">
        <div
            class="alert alert-light d-flex flex-column align-items-center justify-content-center">
            Подготавливаем данные...
            <div class="spinner-border text-primary my-3" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
    </template>

    <template v-else>
        <!-- Карточки историй -->
        <div class="row row-cols-1 row-cols-md-1 g-1">
            <div v-for="(story, index) in stories" :key="story.id" class="col">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <div
                            class="story-item rounded-circle overflow-hidden cursor-pointer border mx-auto"
                            :class="{
              'border-primary': !isStoryViewed(story.id),
              'border-secondary': isStoryViewed(story.id),
            }"
                            style="width: 80px; height: 80px"
                            @click="openStory(index)"
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
                        <button
                            type="button"
                            class="btn btn-link text-danger" @click="deleteStory(story.id)">Удалить
                        </button>
                    </div>
                </div>
            </div>
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
                        :src="stories[currentStory].image"
                        class="w-100 h-100 object-fit-cover"
                        alt="Story"
                    />


                    <!-- Текстовая информация -->
                    <div class="text-white p-3 bg-black bg-opacity-50 position-absolute bottom-0 start-0 end-0">


                        <h5 class="mb-1">{{ stories[currentStory].title }}</h5>
                        <p class="mb-2">{{ stories[currentStory].description }}</p>

                        <template v-if="stories[currentStory].link">
                            <a :href="stories[currentStory].link"
                               v-if="stories[currentStory].link_type==='url'||stories[currentStory].link_type==='bot'"
                               target="_blank" class="btn btn-primary rounded-5 w-100 p-3">
                                Перейти по ссылке
                            </a>

                            <a
                                href="javascript:void(0)"
                                @click="goToProductLink(stories[currentStory].link)"
                                v-if="stories[currentStory].link_type==='product'"
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
            currentStory: null,
            progress: 0,
            timer: null,
            loadingStories: false,
            stories: [],
            stories_paginate_object: null,
            formStory: {
                title: null,
                need_auto_send_stories: true,
                thumbnail: null,
                image: null,
                description: null,
                link: null,
                link_type: "product"
            },
            link_types: [
                {
                    key: "product",
                    title: "Открывает товар в магазине"
                },
                {
                    key: "bot",
                    title: "Открывает раздел бота"
                },
                {
                    key: "url",
                    title: "Переход на внешнюю страницу"
                },
            ],
            isEditing: false,
            useThumbnailFile: true,
            useImageFile: true,
            viewedStories: JSON.parse(localStorage.getItem('viewedStories')) || [],
        };
    },
    mounted() {
        this.loadStoriesList()
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
            this.markAsViewed(this.stories[index].id);
        },
        closeStory() {
            this.currentStory = null;
            this.progress = 0;
            clearInterval(this.timer);
        },
        nextStory() {
            if (this.currentStory < this.stories.length - 1) {
                // Переход к следующей истории
                this.currentStory++;
                this.progress = 0;
                this.markAsViewed(this.stories[this.currentStory].id);
                this.startTimer();
            } else {
                // Закрытие модального окна, если это последняя история
                this.closeStory();
            }
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
        loadStoriesList(page = 1) {
            this.loadingStories = true
            return this.$store.dispatch("loadStories", {
                page: page,
                size: 20
            }).then(() => {

                this.stories = this.$store.getters["getStories"]
                this.stories_paginate_object = this.$store.getters["getStoriesPaginateObject"]
                this.loadingStories = false
            }).catch(() => {
                this.loadingStories = false
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
            this.useThumbnailFile = true;
            this.useImageFile = true;
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

            data.append('thumbnail[]', this.formStory.thumbnail)
            data.append('image[]', this.formStory.image)

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
                    document.getElementById("story-form").reset();
                    this.loadStoriesList()
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
        handleThumbnailUpload(event, param) {
            const file = event.target.files[0];
            if (file && file.size <= 5 * 1024 * 1024) {
                this.formStory[param] = file;
            } else {
                alert('Размер файла миниатюры не должен превышать 5 МБ');
                event.target.value = null;
            }
        },
        getPhoto(imgObject) {
            return {imageUrl: URL.createObjectURL(imgObject)}
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

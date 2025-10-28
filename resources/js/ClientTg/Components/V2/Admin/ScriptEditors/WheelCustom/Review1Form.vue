<template>
    <form class="card" v-on:submit.prevent="submitReview">

        <div class="card-body">
            <h3 class="fw-bold text-primary text-center my-3">
                Оставить отзыв
            </h3>
            <div class="form-check mb-2">
                <input class="form-check-input"
                       v-model="form.review_rating"
                       value="5"
                       type="radio" name="review_rating" id="review_ratingReview1"/>
                <label class="form-check-label" for="review_ratingReview1">
                    Очень хорошо
                </label>
            </div>
            <div class="form-check mb-2">
                <input class="form-check-input"
                       v-model="form.review_rating"
                       value="4"
                       type="radio" name="review_rating" id="review_ratingReview2"/>
                <label class="form-check-label" for="review_ratingReview2">
                    Хорошо
                </label>
            </div>
            <div class="form-check mb-2">
                <input class="form-check-input"
                       value="3"
                       v-model="form.review_rating"
                       type="radio" name="review_rating" id="review_ratingReview3"/>
                <label class="form-check-label" for="review_ratingReview3">
                    Средне
                </label>
            </div>
            <div class="form-check mb-2">
                <input class="form-check-input"
                       v-model="form.review_rating"
                       value="2"
                       type="radio" name="review_rating" id="review_ratingReview4"/>
                <label class="form-check-label" for="review_ratingReview4">
                    Плохо
                </label>
            </div>
            <div class="form-check mb-2">
                <input class="form-check-input"
                       v-model="form.review_rating"
                       value="1"
                       type="radio" name="review_rating" id="review_ratingReview5"/>
                <label class="form-check-label" for="review_ratingReview5">
                    Очень плохо
                </label>
            </div>

            <h6 class="my-3 text-center fw-bold">Прикрепить фотографию</h6>
            <div class="photo-preview d-flex justify-content-center flex-wrap w-100 my-3">
                <label for="menu-photos" style="margin-right: 10px;" class="photo-loader ml-2">
                    <span class="text-primary fw-bold">+</span>
                    <input type="file" id="menu-photos" accept="image/*"
                           @change="onChangePhotos"
                           style="display:none;"/>

                </label>
                <div class="mb-2 img-preview" style="margin-right: 10px;"
                     v-if="form.file_1">
                    <img v-lazy="getPhoto(form.file_1).imageUrl">
                    <div class="remove">
                        <a @click="removePhoto()">Удалить</a>
                    </div>
                </div>

            </div>


            <p class="text-center"><strong>Напишите ваш отзыв</strong></p>

            <div class="form-floating">
                        <textarea class="form-control"
                                  style="min-height:250px;"
                                  v-model="form.review_text"
                                  placeholder="Leave a comment here"
                                  id="floatingTextarea" required></textarea>
                <label for="floatingTextarea">Текст отзыва</label>
            </div>


            <button type="submit"

                    class="btn btn-primary my-2 w-100 p-3">Оставить отзыв</button>
            <button type="button" class="btn btn-link my-3 w-100 text-center" data-bs-dismiss="modal" aria-label="Close">
                Закрыть
            </button>
        </div>


    </form>
</template>
<script>
export default {
    props: ["review"],
    data() {
        return {
            form: {
                review_text: null,
                review_rating: 5,
                file_1: null
            }
        }
    },
    mounted() {

    },
    methods: {
        onChangePhotos(e) {
            const file = e.target.files[0]
            this.form.file_1 = file
        },
        getPhoto(imgObject) {
            return {imageUrl: URL.createObjectURL(imgObject)}
        },
        removePhoto() {
            this.form.file_1 = null
        },
        submitReview() {
            this.$emit("callback", this.form)
        },
    }
}
</script>

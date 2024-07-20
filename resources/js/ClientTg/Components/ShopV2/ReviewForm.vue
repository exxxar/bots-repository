<template>
    <form class="p-2" v-on:submit.prevent="submitReview">
        <slot name="title"></slot>

        <div class="form-check mb-2">
            <input class="form-check-input"
                   v-model="form.rating"
                   value="5"
                   type="radio" name="rating" id="ratingReview1"/>
            <label class="form-check-label" for="ratingReview1">
                Очень хорошо
            </label>
        </div>
        <div class="form-check mb-2">
            <input class="form-check-input"
                   v-model="form.rating"
                   value="4"
                   type="radio" name="rating" id="ratingReview2"/>
            <label class="form-check-label" for="ratingReview2">
                Хорошо
            </label>
        </div>
        <div class="form-check mb-2">
            <input class="form-check-input"
                   value="3"
                   v-model="form.rating"
                   type="radio" name="rating" id="ratingReview3"/>
            <label class="form-check-label" for="ratingReview3">
                Средне
            </label>
        </div>
        <div class="form-check mb-2">
            <input class="form-check-input"
                   v-model="form.rating"
                   value="2"
                   type="radio" name="rating" id="ratingReview4"/>
            <label class="form-check-label" for="ratingReview4">
                Плохо
            </label>
        </div>
        <div class="form-check mb-2">
            <input class="form-check-input"
                   v-model="form.rating"
                   value="1"
                   type="radio" name="rating" id="ratingReview5"/>
            <label class="form-check-label" for="ratingReview5">
                Очень плохо
            </label>
        </div>

<!--        <h6 class="my-3 text-center fw-bold">Прикрепить фотографию</h6>
        <div class="photo-preview d-flex justify-content-center flex-wrap w-100 my-3">
            <label for="menu-photos" style="margin-right: 10px;" class="photo-loader ml-2">
                <span class="text-primary fw-bold">+</span>
                <input type="file" id="menu-photos" accept="image/*"
                       @change="onChangePhotos"
                       style="display:none;"/>

            </label>
            <div class="mb-2 img-preview" style="margin-right: 10px;"
                 v-if="form.image">
                <img v-lazy="getPhoto(form.image).imageUrl">
                <div class="remove">
                    <a @click="removePhoto()">Удалить</a>
                </div>
            </div>

        </div>-->


        <p class="text-center"><strong>Напишите ваш отзыв</strong></p>

        <div class="form-floating">
                        <textarea class="form-control"
                                  style="min-height:250px;"
                                  v-model="form.text"
                                  placeholder="Leave a comment here"
                                  id="floatingTextarea" required></textarea>
            <label for="floatingTextarea">Текст отзыва</label>
        </div>


        <button type="submit"

                class="btn btn-primary my-2 w-100 p-3">Оставить отзыв</button>
        <button type="button" class="btn btn-link my-3 w-100 text-center" data-bs-dismiss="modal" aria-label="Close">
            Закрыть
        </button>
    </form>
</template>
<script>
export default {
    props: ["review"],
    data() {
        return {
            form: {
                id: null,
                text: null,
                rating: 5,
                image: null,
                order_id:null,
                product_id:null,
            }
        }
    },
    mounted() {
        this.$nextTick(() => {
            this.form.id = this.review.id || null
            this.form.order_id = this.review.order_id || null
            this.form.product_id = this.review.product_id || null
        })
    },
    methods: {
        onChangePhotos(e) {
            const file = e.target.files[0]
            this.form.image = file
        },
        getPhoto(imgObject) {
            return {imageUrl: URL.createObjectURL(imgObject)}
        },
        removePhoto() {
            this.form.image = null
        },
        submitReview() {
            let data = new FormData();

            this.sending = true
            Object.keys(this.form)
                .forEach(key => {
                    const item = this.form[key] || ''
                    if (typeof item === 'object')
                        data.append(key, JSON.stringify(item))
                    else
                        data.append(key, item)
                });

            if (typeof this.form.image != "string") {
                data.append('photo', this.form.image);
                data.delete("image")
            }

            this.$store.dispatch("storeReview", {
                reviewForm: data

            }).then((response) => {

                this.$notify({
                        title: "Обратная связь",
                        text: "Спасибо за отзыв",
                    },
                );

                document.querySelectorAll('[data-bs-dismiss="modal"]').forEach(item=>item.click())

                this.$emit("callback", response.data)

            }).catch(err => {

            })
        },
    }
}
</script>

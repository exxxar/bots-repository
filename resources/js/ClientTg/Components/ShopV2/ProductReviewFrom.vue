<template>
    <form class="p-2" action="">
        <p class="text-center"><strong>Поставьте оценку этому блюду</strong></p>

        <div class="form-check mb-2">
            <input class="form-check-input" type="radio" name="exampleForm" id="radio3Example1" />
            <label class="form-check-label" for="radio3Example1">
                Очень хорошо
            </label>
        </div>
        <div class="form-check mb-2">
            <input class="form-check-input" type="radio" name="exampleForm" id="radio3Example2" />
            <label class="form-check-label" for="radio3Example2">
                Хорошо
            </label>
        </div>
        <div class="form-check mb-2">
            <input class="form-check-input" type="radio" name="exampleForm" id="radio3Example3" />
            <label class="form-check-label" for="radio3Example3">
                Средне
            </label>
        </div>
        <div class="form-check mb-2">
            <input class="form-check-input" type="radio" name="exampleForm" id="radio3Example4" />
            <label class="form-check-label" for="radio3Example4">
                Плохо
            </label>
        </div>
        <div class="form-check mb-2">
            <input class="form-check-input" type="radio" name="exampleForm" id="radio3Example5" />
            <label class="form-check-label" for="radio3Example5">
                Очень плохо
            </label>
        </div>

        <p class="text-center"><strong>Напиши ваш отзыв</strong></p>

        <div class="form-floating">
                        <textarea class="form-control"
                                  style="min-height:250px;"
                                  placeholder="Leave a comment here"
                                  id="floatingTextarea"></textarea>
            <label for="floatingTextarea">Текст отзыва</label>
        </div>
    </form>
</template>
<script>
export default {

}
</script>

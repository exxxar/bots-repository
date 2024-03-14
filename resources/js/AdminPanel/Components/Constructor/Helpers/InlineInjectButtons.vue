<template>
    <div class="dropdown">
        <button class="btn btn-link dropdown-toggle" type="button" data-bs-toggle="dropdown"
                aria-expanded="false">
            Кнопки
        </button>
        <ul class="dropdown-menu">
            <li
                @click="attachTo(item.value)"
                v-for="item in htmlInjections"
            ><a class="dropdown-item"
                href="javascript:void(0)">{{ item.title || 'Не указано' }}</a></li>

        </ul>
    </div>
</template>
<script>
export default {
    props:['param'],
    data() {
        return {
            htmlInjections: [
                {
                    title: "Кнопка синяя",
                    value: "<a href='http://test.com/test' class='w-100 btn btn-primary'>Текст кнопки</a>"
                },
                {
                    title: "Кнопка без цвета",
                    value: "<a href='http://test.com/test' class='w-100 btn btn-link'>Текст кнопки</a>"
                },
                {
                    title: "Кнопка с синей обводкой",
                    value: "<a href='http://test.com/test' class='w-100 btn btn-outline-primary'>Текст кнопки</a>"
                }
            ],
        }
    },
    mounted() {

    },
    methods: {
        attachTo(value) {

            this.$emit("callback", {
                param: this.param || null,
                value: value
            })
        }
    }
}
</script>

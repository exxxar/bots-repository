<template>

    <ul class="list-group">
        <li
            v-for="(item, index) in sorted"
            class="list-group-item d-flex justify-content-between"
            v-bind:class="{'current-day text-primary fw-bold':preparedDay == index}"
            aria-current="true">
            <span>{{ item.day || '-' }}</span>
            <span
                v-if="!item.closed"
                class="text-primary fw-bold">{{ item.start_at || '-' }} - {{ item.end_at || '-' }}</span>

            <span
                v-if="item.closed"
                class="text-primary fw-bold">{{ item.closed_comment || '-' }}</span>
        </li>


    </ul>
</template>
<script>
export default {
    props: ["schedule"],
    computed: {
        preparedDay() {
            let day = (new Date()).getUTCDay();

            return day === 0 ? 6 : day - 1
        },
        sorted() {
            const map = {
                'Понедельник': 1, 'Вторник': 2, 'Среда': 3, 'Четверг': 4, 'Пятница': 5, 'Суббота': 6, 'Воскресенье': 7
            };

            return this.schedule.sort((a, b) => {
                return map[a.day] - map[b.day];
            })
        }
    }
}
</script>

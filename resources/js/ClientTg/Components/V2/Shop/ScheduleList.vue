<template>

    <ul class="list-group">
        <li
            v-for="(item, index) in sorted"
            class="list-group-item d-flex justify-content-between"
            v-bind:class="{'current-day text-primary fw-bold':(new Date()).getUTCDay()-1 === index}"
            aria-current="true">
            <span>{{item.day || '-'}}</span>
            <span
                v-if="!item.closed"
                class="text-primary fw-bold">{{item.start_at || '-'}} - {{item.end_at || '-'}}</span>

            <span
                v-if="item.closed"
                class="text-primary fw-bold">{{item.closed_comment || '-'}}</span>
        </li>


    </ul>
</template>
<script>
export default {
    props:["schedule"],
    computed:{
        sorted(){
            const map = {
                'Понедельник': 1,'Вторник': 2,'Среда': 3,'Четверг': 4,'Пятница': 5,'Суббота': 6, 'Воскресенье': 7
            };

            let tmp = this.schedule.sort((a, b) => {
                return map[a.day] - map[b.day];
            });

            return tmp
        }
    }
}
</script>

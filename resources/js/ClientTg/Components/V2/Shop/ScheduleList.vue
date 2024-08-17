<template>

    <ul class="list-group">
        <li
            v-for="(item, index) in schedule"
            class="list-group-item d-flex justify-content-between"
            v-bind:class="{'current-day text-primary fw-bold':(new Date()).getUTCDay() === index}"
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
    props:["schedule"]
}
</script>

<template>
    <p class="mb-0">
        <i class="fa fa-star"
           v-bind:class="{'color-yellow1-dark':rating>=1,'color-gray1-dark':rating<1}"
        ></i>
        <i class="fa fa-star"
           v-bind:class="{'color-yellow1-dark':rating>=2,'color-gray1-dark':rating<2}"
        ></i>
        <i class="fa fa-star"
           v-bind:class="{'color-yellow1-dark':rating>=3,'color-gray1-dark':rating<3}"
        ></i>
        <i class="fa fa-star"
           v-bind:class="{'color-yellow1-dark':rating>=4,'color-gray1-dark':rating<4}"
        ></i>
        <i class="fa fa-stark"
           v-bind:class="{'color-yellow1-dark':rating>=5,'color-gray1-dark':rating<5}"
        ></i>
    </p>
</template>
<script>
export default {
    props: {
        rating: {
            type: Number,
            default: 0
        }
    }
}
</script>

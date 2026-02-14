<template>
    <div
        :id="'carousel-' + block.id"
        class="carousel slide"
        :class="[
                block.props.block ? 'w-100' : '',
                block.props.marginTop,
                block.props.marginRight,
                block.props.marginBottom,
                block.props.marginLeft,
                 block.props.paddingTop,
                block.props.paddingRight,
                block.props.paddingBottom,
                block.props.paddingLeft,
]"

        :style="[
                     isSelected(block.id)?'border:2px red dashed':'',
                    ]"

        :data-bs-ride="block.props.interval ? 'carousel' : null"
        :data-bs-interval="block.props.interval"
        @click.stop="$emit('select', block.id)"
    >

        <!-- Indicators -->
        <div v-if="block.props.showIndicators" class="carousel-indicators">
            <button
                v-for="(img, i) in block.props.images"
                :key="img.id"
                type="button"
                :data-bs-target="'#carousel-' + block.id"
                :data-bs-slide-to="i"
                :class="{ active: i === 0 }"
            ></button>
        </div>

        <!-- Slides -->
        <div class="carousel-inner">
            <div
                v-for="(img, i) in block.props.images"
                :key="img.id"
                :class="['carousel-item', { active: i === 0 }]"
            >
                <img v-lazy="img.src" class="d-block w-100" :alt="img.alt">
            </div>
        </div>

        <!-- Controls -->
        <button
            v-if="block.props.showControls"
            class="carousel-control-prev"
            type="button"
            :data-bs-target="'#carousel-' + block.id"
            data-bs-slide="prev"
        >
            <span class="carousel-control-prev-icon"></span>
        </button>

        <button
            v-if="block.props.showControls"
            class="carousel-control-next"
            type="button"
            :data-bs-target="'#carousel-' + block.id"
            data-bs-slide="next"
        >
            <span class="carousel-control-next-icon"></span>
        </button>

    </div>
</template>

<script>
export default {
    props: ['block', 'isSelected']
}
</script>

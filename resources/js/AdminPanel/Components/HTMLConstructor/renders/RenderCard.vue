<script setup>
import Renderer from '@/AdminPanel/Components/HTMLConstructor/Renderer.vue'
</script>
<template>
    <div
        :style="[
                     isSelected(block.id)?'border:2px red dashed':'',
                    ]"

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
        class="card" @click.stop="select">
        <div class="card-body">
            <h5 class="card-title">{{ block.props.title }}</h5>
            <p class="card-text">{{ block.props.text }}</p>
            <Renderer
                :is-selected="isSelected"
                :blocks="block.children || []" @select="$emit('select', $event)" />
        </div>
    </div>
</template>

<script>


export default {
    name: 'RenderCard',

    props: {
        block: {
            type: Object,
            required: true
        },
        isSelected:{}
    },
    methods: {
        select() {
            this.$emit('select', this.block.id)
        }
    }
}
</script>

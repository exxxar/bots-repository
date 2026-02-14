<script setup>
import Renderer from '@/AdminPanel/Components/HTMLConstructor/Renderer.vue'
</script>
<template>

    <div
        class="position-relative"
        :class="[
                block.props.block ? 'w-100' : '',
                /*isSelected(block.id) ? 'selected-block' : '',*/
        ]"
        @click.stop="select"
    >
        <button class="btn mb-2"
                :style="[
                     isSelected(block.id)?'border:2px red dashed':'',
                    ]"
                :class="[
                'btn-' + block.props.variant,

                block.props.size !== 'md' ? 'btn-' + block.props.size : '',
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
        >{{ block.props.text }}
        </button>

    </div>

</template>

<script>
export default {
    name: 'RenderButton',
    props: {
        block: {
            type: Object,
            required: true
        },
        isSelected: {}
    },
    methods: {
        select() {
            this.$emit('select', this.block.id)
        }
    }
}
</script>

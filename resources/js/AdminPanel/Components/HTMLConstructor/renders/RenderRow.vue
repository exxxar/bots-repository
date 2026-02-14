<script setup>
import Renderer from '@/AdminPanel/Components/HTMLConstructor/Renderer.vue'
</script>
<template>
    <div
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
                     isSelected(block.id)?'border:2px red dashed !important; min-height:10px;':'border:1px gray dashed; min-height:10px;',
                    ]"
        class="row p-2"

        @click.stop="select">
        <Renderer :is-selected="isSelected" :blocks="block.children" @select="$emit('select', $event)" />

    </div>
</template>

<script>

import draggable from 'vuedraggable'
export default {
    name: 'RenderRow',
    components: { draggable },
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

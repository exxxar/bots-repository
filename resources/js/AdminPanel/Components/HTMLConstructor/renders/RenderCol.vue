<script setup>
import Renderer from '@/AdminPanel/Components/HTMLConstructor/Renderer.vue'
</script>
<template>

    <div

        style="border:1px gray dashed; min-height:10px;"

        :style="[
                     isSelected(block.id)?'border:2px red dashed':'',
                    ]"

        :class="[
            'col-' + props?.size||12,
                block.props.marginTop,
                block.props.marginRight,
                block.props.marginBottom,
                block.props.marginLeft,
                 block.props.paddingTop,
                block.props.paddingRight,
                block.props.paddingBottom,
                block.props.paddingLeft,

                // FLEX
                block.props.flexEnabled ? 'd-flex' : '',
                block.props.flexEnabled ? 'flex-' + block.props.flexDirection : '',
                block.props.flexEnabled ? 'justify-content-' + block.props.justifyContent : '',
                block.props.flexEnabled ? 'align-items-' + block.props.alignItems : '',
                block.props.flexEnabled ? 'flex-' + block.props.flexWrap : '',

             props?.showBorder ? 'border border-primary' : '' ]"
        @click.stop="select">
        <Renderer
            :is-selected="isSelected"
            :blocks="block.children" @select="$emit('select', $event)"/>
    </div>
</template>

<script>



export default {
    name: 'RenderCol',

    props: {
        block: {
            type: Object,
            required: true
        },
        isSelected: {}
    },
    computed: {
        props() {
            if (!this.block)
                return null
            return this.block.props || this.block.defaultProps
        }
    },
    methods: {
        select() {
            this.$emit('select', this.block.id)
        }
    }
}
</script>

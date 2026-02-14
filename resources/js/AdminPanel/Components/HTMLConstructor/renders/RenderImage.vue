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
        class="mb-2" @click.stop="select">
        <img
            v-lazy="block.props.src"
            :alt="block.props.alt"
            :class="[
        block.props.fluid ? 'img-fluid' : '',
        block.props.rounded ? 'rounded' : ''
      ]"
           :style="[

            isSelected(block.id)?'border:2px red dashed':'',

            block.props?.height ? 'height:'+ block.props?.height+'px;':''
]"
        />
    </div>
</template>

<script>
export default {
    name: 'RenderImage',
    props: ['block','isSelected'],
    methods: {
        select() {
            this.$emit('select', this.block.id)
        }
    }
}
</script>

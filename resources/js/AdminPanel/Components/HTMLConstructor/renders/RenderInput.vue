<template>
    <input
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

        :type="block.props.type"
        class="form-control"
        :placeholder="block.props.placeholder"
        :value="block.props.value"
        @click.stop="$emit('select', block.id)"
        readonly
    />
</template>

<script>
export default {
    props: ['block','isSelected']
}
</script>

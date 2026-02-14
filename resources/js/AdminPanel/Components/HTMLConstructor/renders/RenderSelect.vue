<template>
    <select

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
        class="form-select"
        @click.stop="$emit('select', block.id)"
    >
        <option
            v-for="(opt, i) in block.props.options"
            :key="i"
            :selected="block.props.selected === opt"
        >
            {{ opt }}
        </option>
    </select>
</template>

<script>
export default {
    props: ['block', 'isSelected']
}
</script>

<template>
  <textarea

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
      class="form-control"
      :rows="block.props.rows"
      :placeholder="block.props.placeholder"
      @click.stop="$emit('select', block.id)"
      readonly
  >{{ block.props.value }}</textarea>
</template>

<script>
export default {
    props: ['block','isSelected']
}
</script>

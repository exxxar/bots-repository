<template>
    <div class="h-100 p-2">
        <h6 class="mb-3">Холст</h6>

        <div class="border rounded p-3 bg-light phone-frame">
            <Renderer
                :is-selected="isSelected"
                @select="$emit('select', $event)"
                :blocks="page"  />
        </div>
    </div>
</template>

<script>
import draggable from 'vuedraggable'
import Renderer from './Renderer.vue'

export default {
    name: 'Canvas',
    components: { Renderer, draggable },
    props: {
        page: Array,
        isSelected: {},
    },
    methods: {
        onUpdate(newList) {
            // Пробрасываем наверх, чтобы App.vue обновил своё состояние
            this.$emit('update:page', newList)
        }
    }
}
</script>

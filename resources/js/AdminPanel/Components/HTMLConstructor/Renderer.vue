
<template>

        <component
            v-for="block in blocks"
            :key="block.id"
            :is="resolve(block)"
            :block="block"
            :is-selected="isSelected"
            @delete="$emit('delete', $event)"
            @select="onSelect"
        />

</template>

<script>
import RenderButton from '@/AdminPanel/Components/HTMLConstructor/renders/RenderButton.vue'
import RenderRow from '@/AdminPanel/Components/HTMLConstructor/renders/RenderRow.vue'
import RenderCol from '@/AdminPanel/Components/HTMLConstructor/renders/RenderCol.vue'
import RenderCard from '@/AdminPanel/Components/HTMLConstructor/renders/RenderCard.vue'
import RenderImage from '@/AdminPanel/Components/HTMLConstructor/renders/RenderImage.vue'
import RenderNavbar from '@/AdminPanel/Components/HTMLConstructor/renders/RenderNavbar.vue'

import RenderText from '@/AdminPanel/Components/HTMLConstructor/renders/RenderText.vue'
import RenderInput from '@/AdminPanel/Components/HTMLConstructor/renders/RenderInput.vue'
import RenderTextarea from '@/AdminPanel/Components/HTMLConstructor/renders/RenderTextarea.vue'
import RenderSelect from '@/AdminPanel/Components/HTMLConstructor/renders/RenderSelect.vue'
import RenderCarousel from '@/AdminPanel/Components/HTMLConstructor/renders/RenderCarousel.vue'
import RenderCollapse from '@/AdminPanel/Components/HTMLConstructor/renders/RenderCollapse.vue'
import RenderBadge from '@/AdminPanel/Components/HTMLConstructor/renders/RenderBadge.vue'
import RenderAlert from '@/AdminPanel/Components/HTMLConstructor/renders/RenderAlert.vue'

export default {
    name: 'Renderer',
    props: {
        blocks: {
            type: Array,
            required: true
        },
        isSelected: {}
    },
    methods: {
        resolve(block) {
            const map = {
                button: RenderButton,
                row: RenderRow,
                col: RenderCol,
                card: RenderCard,
                image: RenderImage,
                navbar: RenderNavbar,
                text: RenderText,
                input: RenderInput,
                textarea: RenderTextarea,
                select: RenderSelect,
                carousel: RenderCarousel,
                collapse: RenderCollapse,
                alert: RenderAlert,
                badge: RenderBadge,


        }
            return map[block.type] || 'div'
        },
        onSelect(id) {
            this.$emit('select', id)
        }
    }
}
</script>

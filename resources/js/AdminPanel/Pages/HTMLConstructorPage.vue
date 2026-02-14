<script setup>
import TreeView from "@/AdminPanel/Components/HTMLConstructor/TreeView.vue";
</script>
<template>
    <div class="container-fluid vh-100 overflow-hidden">


        <nav class="navbar navbar-expand-lg bg-body-tertiary">
            <div class="container-fluid">
                <a class="navbar-brand fw-bold" href="#">Конструктор</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                        data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                        aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li
                            v-if="selectedComponentId"
                            class="nav-item">
                            <a

                                @click="selectedComponentId = null"
                                class="nav-link active text-danger"
                                aria-current="page"
                                href="#"
                            >
                                <i class="fa-solid fa-circle-xmark me-1"></i>
                                Убрать выделение
                            </a>
                        </li>

                        <li
                            v-if="page.length>0"
                            class="nav-item">
                            <a class="nav-link"
                               @click="clearCanvas"
                               href="#"
                            >
                                <i class="fa-solid fa-trash me-1"></i>
                                Очистить холст
                            </a>
                        </li>

                        <li
                            v-if="page.length>0"
                            class="nav-item">
                            <a class="nav-link"
                               @click="toggleHtmlPanel"
                               href="#"
                            >
                                <i class="fa-solid fa-code me-1"></i>
                                {{ showHtmlPanel ? 'Скрыть HTML' : 'Показать HTML' }}
                            </a>
                        </li>

                    </ul>


                </div>
            </div>
        </nav>
        <div class="row h-100 ">

            <!-- Левая панель -->
            <div class="col-2 p-0 border-end">
                <Palette @add="onAddComponent"/>
            </div>

            <!-- Центральная зона -->
            <div class="col-7 p-0 d-flex flex-column">

                <!-- Мобильный холст -->
                <div class="flex-grow-1 d-flex justify-content-center align-items-start p-3">
                    <div class="phone-frame shadow-sm">

                        <Canvas :page="page"
                                :is-selected="isSelected"
                                @update:page="page = $event"
                                @select="onSelectComponent"/>
                    </div>
                </div>

            </div>

            <!-- Правая панель свойств -->
            <div class="col-3 p-0 border-start">

                <ul class="nav nav-tabs">
                    <li class="nav-item">
                        <a
                            @click="propertyTab='property'"
                            v-bind:class="{'active': propertyTab==='property'}"
                            class="nav-link" aria-current="page" href="javascript:void(0)">Свойство</a>
                    </li>
                    <li class="nav-item" v-if="page.length>0">
                        <a class="nav-link"
                           @click="propertyTab='tree'"
                           v-bind:class="{'active': propertyTab==='tree'}"
                           href="javascript:void(0)">Дерево компонентов</a>
                    </li>
                </ul>

                <template v-if="propertyTab==='property'">
                    <div class="container py-2">
                        <h6 class="mb-3">Свойства</h6>
                        <PropertiesPanel :component="selectedComponent"
                                         @add-col="onAddCol"
                                         @add-element-to-col="onAddElement"
                                         @delete-component="onDeleteComponent"
                                         @duplicate-component="duplicate"
                                         @move-up-component="moveUp"
                                         @move-down-component="moveDown"
                        />
                    </div>
                </template>
                <template v-if="propertyTab==='tree'">
                    <div class="container py-2">
                        <h6 class="mb-3">Структура страницы</h6>
                        <TreeView
                            :blocks="page"
                            @dblClick="propertyTab='property'"
                            :selected-id="selectedComponentId"
                            @select="onSelectComponent"
                        />
                    </div>

                </template>


            </div>
        </div>

        <!-- Выезжающая панель HTML -->
        <div
            class="html-panel shadow-lg"
            :class="{ open: showHtmlPanel }"
        >
            <div class="d-flex justify-content-between align-items-center p-2 border-bottom">
                <h6 class="mb-0">Сгенерированный HTML</h6>
                <button class="btn btn-sm btn-outline-secondary" @click="copyHTML">
                    Копировать
                </button>
            </div>

            <textarea
                class="form-control border-0 rounded-0"
                rows="20"
                readonly
                :value="generatedHTML"
            />
        </div>


    </div>
</template>

<script>
import Palette from '@/AdminPanel/Components/HTMLConstructor/Palette.vue'
import Canvas from '@/AdminPanel/Components/HTMLConstructor/Canvas.vue'
import PropertiesPanel from '@/AdminPanel/Components/HTMLConstructor/PropertiesPanel.vue'

import {
    createEmptyPage,
    createComponentByType,
    addComponentToPage,
    findComponentById,
    findParentAndIndex,

    moveComponent,
    insertNextTo
} from '@/AdminPanel/Components/HTMLConstructor/modules/pageStore'
import {renderPageHTML} from '@/AdminPanel/Components/HTMLConstructor/modules/htmlRenderer'

export default {
    name: 'App',
    components: {
        Palette,
        Canvas,
        PropertiesPanel
    },
    data() {
        return {
            page: createEmptyPage(),
            selectedComponentId: null,
            showHtmlPanel: false,
            propertyTab: 'property',
        }
    },
    computed: {
        selectedComponent() {
            return findComponentById(this.page, this.selectedComponentId)
        },
        generatedHTML() {
            return renderPageHTML(this.page)
        },
        isSelected() {
            return id => this.selectedComponentId === id
        }
    },
    methods: {
        clearCanvas() {
            this.page = []
            this.selectedComponentId = null
        },
        onSelectComponent(id) {
            this.selectedComponentId = id
        },
        toggleHtmlPanel() {
            this.showHtmlPanel = !this.showHtmlPanel
        },
        copyHTML() {
            navigator.clipboard.writeText(this.generatedHTML).catch(() => {
            })
        },
        onAddCol(rowId) {
            const row = findComponentById(this.page, rowId)
            if (!row) return

            row.children.push({
                id: 'col_' + Math.random().toString(36).substr(2, 9),
                type: 'col',
                props: {size: 6},
                children: []
            })
        },
        onAddComponent(type) {
            const newCmp = createComponentByType(type)

            // 1) Если есть выбранный компонент — добавляем внутрь него
            if (this.selectedComponentId) {
                const target = findComponentById(this.page, this.selectedComponentId)


                if (target && target.children) {
                    if (!Array.isArray(target.children)) {
                        target.children = []
                    }

                    target.children.push(newCmp)
                    // this.selectedComponentId = newCmp.id
                    return
                }
            }


            this.page.push(newCmp)
            //this.selectedComponentId = newCmp.id
        },

        handleAddCol() {
            const selected = findComponentById(this.page, this.selectedComponentId)

            // 1) Если ничего не выбрано → создаём row → col
            if (!selected) {
                const row = createComponentByType('row')
                const col = createComponentByType('col')
                row.children.push(col)
                this.page.push(row)
                return
            }

            // 2) Если выбран row → добавляем col внутрь него
            if (selected.type === 'row') {
                const col = createComponentByType('col')
                selected.children.push(col)
                return
            }

            // 3) Если выбран col → добавляем col внутрь родительского row
            if (selected.type === 'col') {
                const parentRow = this.findParentRow(this.page, selected.id)
                if (parentRow) {
                    const col = createComponentByType('col')
                    parentRow.children.push(col)
                }
                return
            }

            // 4) Если выбран другой компонент → создаём row → col
            const row = createComponentByType('row')
            const col = createComponentByType('col')
            row.children.push(col)
            this.page.push(row)
        },
        findParentRow(blocks, childId) {
            for (const block of blocks) {
                if (block.type === 'row' && block.children.some(c => c.id === childId)) {
                    return block
                }
                if (block.children) {
                    const found = this.findParentRow(block.children, childId)
                    if (found) return found
                }
            }
            return null
        },
        addElementToSelected(type) {
            if (!this.selectedComponentId) return

            const target = findComponentById(this.page, this.selectedComponentId)
            if (!target) return

            if (!Array.isArray(target.children)) {
                target.children = []
            }

            const newCmp = createComponentByType(type)
            target.children.push(newCmp)
            this.selectedComponentId = newCmp.id
        },

        onAddElement({type}) {
            if (!type) return
            this.addElementToSelected(type)
        },


        duplicate(id) {
            const cmp = findComponentById(this.page, id)
            if (!cmp) return

            const clone = JSON.parse(JSON.stringify(cmp))
            clone.id = 'cmp_' + Math.random().toString(36).substr(2, 9)

            insertNextTo(this.page, id, clone)

        },

        moveUp(id) {
            moveComponent(this.page, id, -1)

        },

        moveDown(id) {
            moveComponent(this.page, id, +1)

        },
        onDeleteComponent(id) {
            this.deleteComponentById(this.page, id)
            this.selectedComponentId = null
        },

        deleteComponentById(list, id) {
            const index = list.findIndex(item => item.id === id)
            if (index !== -1) {
                list.splice(index, 1)
                return true
            }

            for (const item of list) {
                if (item.children && item.children.length) {
                    const deleted = this.deleteComponentById(item.children, id)
                    if (deleted) return true
                }
            }

            return false
        },
    }
}
</script>

<style scoped>
/* Эмуляция мобильного экрана */
.phone-frame {
    width: 390px;
    height: 600px;
    background: #fff;
    border-radius: 20px;
    border: 1px solid #ddd;
    overflow-y: auto;
    padding: 10px;
}

/* Выезжающая панель HTML */
.html-panel {
    position: fixed;
    top: 0;
    right: -450px;
    width: 450px;
    height: 100%;
    background: #fff;
    transition: right 0.3s ease;
    z-index: 1000;
    display: flex;
    flex-direction: column;
}

.html-panel.open {
    right: 0;
}

.selected-block {
    outline: 2px solid #0d6efd;
    border-radius: 6px;
}

.delete-btn {
    position: absolute;
    top: -8px;
    right: -8px;
    background: #dc3545;
    color: #fff;
    border: none;
    width: 22px;
    height: 22px;
    border-radius: 50%;
    font-size: 12px;
    cursor: pointer;
}

.context-menu {
    position: fixed;
    background: white;
    border: 1px solid #ddd;
    border-radius: 6px;
    z-index: 9999;
    padding: 4px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
}

.context-menu button {
    display: block;
    width: 100%;
    padding: 6px 12px;
    background: none;
    border: none;
    text-align: left;
}

.context-menu button:hover {
    background: #f0f0f0;
}

</style>

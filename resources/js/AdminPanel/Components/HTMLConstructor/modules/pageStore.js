// Логика работы со страницей: добавление, поиск, выбор

import {COMPONENT_LIBRARY} from './componentLibrary'

function clone(obj) {
    return JSON.parse(JSON.stringify(obj))
}

export function createEmptyPage() {
    return []
}

export function createComponentByType(type) {
    const lib = COMPONENT_LIBRARY.find(c => c.type === type)
    if (!lib) return null

    const id = 'cmp_' + Math.random().toString(36).substr(2, 9)

    let res = {
        id,
        type: lib.type,
        props: clone(lib.defaultProps || {}),

    }

    if (lib.children)
        res.children = clone(lib.children)



    return res
}


export function addComponentToPage(page, type) {
    const cmp = createComponentByType(type)
    if (!cmp) return page
    page.push(cmp)
    return page
}

export function findComponentById(blocks, id) {
    for (const b of blocks) {
        if (b.id === id) return b
        if (b.children && b.children.length) {
            const found = findComponentById(b.children, id)
            if (found) return found
        }
    }
    return null
}


// Универсальный поиск родителя и индекса
export function findParentAndIndex(list, id) {
    for (let i = 0; i < list.length; i++) {
        const item = list[i]
        if (item.id === id) {
            return {parent: list, index: i}
        }
        if (item.children && item.children.length) {
            const result = findParentAndIndex(item.children, id)
            if (result) return result
        }
    }
    return null
}

// Перемещение вверх/вниз
export function moveComponent(list, id, direction) {
    const found = findParentAndIndex(list, id)
    if (!found) return

    const {parent, index} = found
    const newIndex = index + direction

    if (newIndex < 0 || newIndex >= parent.length) return

    const temp = parent[index]
    parent[index] = parent[newIndex]
    parent[newIndex] = temp
}

// Вставка копии рядом с оригиналом
export function insertNextTo(list, id, newComponent) {
    const found = findParentAndIndex(list, id)
    if (!found) return

    const {parent, index} = found
    parent.splice(index + 1, 0, newComponent)
}



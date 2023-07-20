import products from './products'
let state = {
...products.state
}

const getters = {
    ...products.getters
}

const actions = {
    ...products.actions

}

const mutations = {
    ...products.mutations
}

const shopAdminModule = {
    state,
    mutations,
    actions,
    getters
}
export default shopAdminModule;

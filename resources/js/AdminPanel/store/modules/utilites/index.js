import axios from "axios";

export default {
    async makeAxiosFactory(link,method = 'GET', data = null, config = null){
        let result;
        switch(method.toUpperCase()) {
            default:
            case 'GET':
                result =  await axios.get(link);
                break;
            case 'POST':
                result =  await axios.post(link, data, config)
                break;
            case 'PUT':
                result =  await axios.put(link, data)
                break;
            case 'DELETE':
                result =  await axios.delete(link)
                break;
        }

        return result;
    },
    async loadActualProducts(ids = []) {

        let link = `/global-scripts/shop/products-by-ids`
        let method = 'POST'

        let _axios = this.makeAxiosFactory(link, method, {
            ids: ids
        })

        return _axios.then((response) => {
            const products = response.data.data;
            return Promise.resolve(products);
        }).catch(err => {
            return Promise.reject(err);
        })
    },
}

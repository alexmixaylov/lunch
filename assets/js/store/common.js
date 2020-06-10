import commonOrder from './modules/order'
import company from "./modules/company";
import menu from './modules/menu'

export default {
    namespaced: true,

    modules:{
        commonOrder,
        company,
        menu
    }

}

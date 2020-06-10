import commonOrder from './modules/order';
import company from "./modules/company";
import menu from './modules/menu';
import user from "./modules/user";

export default {
    namespaced: true,

    modules:{
        commonOrder,
        company,
        menu,
        user
    }

}

import axios from 'axios';
import commonOrder from './modules/common-order'
import company from "./modules/company";

export default {
    namespaced: true,

    modules:{
        commonOrder,
        company
    }

}

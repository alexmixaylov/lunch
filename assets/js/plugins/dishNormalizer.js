const encodeOrders = function (dishes = []) {
    let orderObj = {};
    dishes.forEach(item => {
        const dishId = item.dish_id;
        if (orderObj.hasOwnProperty(dishId)) {
            orderObj[dishId].cnt++
            orderObj[dishId].summ = orderObj[dishId].summ + orderObj[dishId].price
        } else {
            orderObj[dishId] = {
                title: item.title,
                price: item.price,
                summ: item.price,
                cnt: 1
            }
        }
    });
    return Object.values(orderObj)
}

const decodeOrders = function(){

}

export {encodeOrders, decodeOrders}

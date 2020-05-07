import dateFormat from 'dateformat';

// Internationalization strings
dateFormat.i18n = {
    dayNames: [
        'Вс','Пон', 'Вт', 'Ср', 'Четв', 'Пят', 'Субб',
        'Воскресенье', 'Понедельник', 'Вторник', 'Среда', 'Четверг', 'Пятница', 'Суббота'
    ],
    monthNames: [
        'Jan', 'Feb', 'Mar', 'Apr', 'Мая', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec',
        'January', 'February', 'March', 'April', 'Мая', 'June', 'July', 'August', 'September', 'October', 'November', 'December'
    ],
    timeNames: [
        'a', 'p', 'am', 'pm', 'A', 'P', 'AM', 'PM'
    ]
};

export {dateFormat}

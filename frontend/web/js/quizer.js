/**
 * Created by zapleo on 25.12.17.
 */
$('.timer').each(function () {
    var elem = $(this);
    var time = elem.text()*1000;
    elem.text('Осталось: '+humanizeDuration(time,{
            units: ['d', 'h', 'm', 's'],
            language: 'shortEn',
            languages: {
                shortEn: {
                    y: function () {
                        return 'г'
                    },
                    mo: function () {
                        return 'мес'
                    },
                    w: function () {
                        return 'н'
                    },
                    d: function () {
                        return 'д'
                    },
                    h: function () {
                        return 'ч'
                    },
                    m: function () {
                        return 'м'
                    },
                    s: function () {
                        return 'с'
                    },
                    ms: function () {
                        return 'мс'
                    },
                }
            }
        }));
    elem.css('display','initial');
    setInterval(function () {
        time -= 1000;
        elem.text('Осталось: '+humanizeDuration(time,{
            units: ['d', 'h', 'm', 's'],
            language: 'shortEn',
            languages: {
                shortEn: {
                    y: function () {
                        return 'г'
                    },
                    mo: function () {
                        return 'мес'
                    },
                    w: function () {
                        return 'н'
                    },
                    d: function () {
                        return 'д'
                    },
                    h: function () {
                        return 'ч'
                    },
                    m: function () {
                        return 'м'
                    },
                    s: function () {
                        return 'с'
                    },
                    ms: function () {
                        return 'мс'
                    },
                }
            }
        }));
    },1000);
});
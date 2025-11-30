var swiper = new Swiper('.mySwiper', { //Instancia del carrusel libreria swiper
    navigation: { //Botones de navegacion
        nextEl: '.swiper-button-next',
        prevEl: '.swiper-button-prev'
    },
    slidesPerView: 1, //Mostrar un slide
    spaceBetween: 10, //Distancia entre cars
    // init: false,
    pagination: { //Paginacion
        el: '.swiper-pagination',
        clickable: true,
    },


    breakpoints: { //Slide responsive
        620: { //Tamaño 620
            slidesPerView: 1,
            spaceBetween: 20,
        },
        680: { //Tamaño 680
            slidesPerView: 2,
            spaceBetween: 40,
        },
        920: { //Tamaño 920
            slidesPerView: 3,
            spaceBetween: 40,
        },
        1240: { //Tamaño 1240
            slidesPerView: 4,
            spaceBetween: 50,
        },
    }
});

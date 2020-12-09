const carouselTitle = document.querySelectorAll('.carousel-title');
const btns = document.querySelectorAll('.btn-carousel');
const l1 = document.querySelector('.l1');
const l2 = document.querySelector('.l2');

window.addEventListener('load', () => {
    const TL = gsap.timeline({paused: true});

    TL
    .staggerFrom(carouselTitle, 1, {top: -50, opacity: 0, ease: "power2.out"}, 0.3)
    .staggerFrom(btns, 1, {opacity: 0, ease: "power2.out"}, 0.3, '-=1')
    .from(l1, 1, {width: 0, ease: "power2.out"}, '-=2')
    .from(l2, 1, {width: 0, ease: "power2.out"}, '-=2')

    TL.play();
    var controller = new ScrollMagic.Controller();

    var tween = TweenMax.from('.container-tab', {left: -700, rotation: -50, ease: Power2.easeInOut});

    var scene = new ScrollMagic.scene
})


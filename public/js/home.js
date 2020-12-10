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
    
    const ratio = .1;
    const options = {
        root: null,
        rootMargin: '0px',
        threshold: ratio
      }
      
      const handleIntersect = function(entries, observer){
          entries.forEach(function(entry){
              if(entry.intersectionRatio > ratio){
                  entry.target.classList.add('reveal-visible')
                  observer.unobserve(entry.target)
              }
          })
      }

      const observer = new IntersectionObserver(handleIntersect, options);
      document.querySelectorAll('.reveal').forEach(function(r){
          observer.observe(r);
      })
})


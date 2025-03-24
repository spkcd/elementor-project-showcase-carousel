(function($) {
    'use strict';

    class ProjectCarousel {
        constructor() {
            this.initCarousels();
            this.initLightbox();
        }

        initCarousels() {
            const carousels = document.querySelectorAll('.project-carousel');
            
            carousels.forEach(carousel => {
                const settings = JSON.parse(carousel.dataset.swiperSettings);
                
                new Swiper(carousel, {
                    ...settings,
                    loop: true,
                    effect: 'slide',
                    speed: 800,
                    grabCursor: true,
                    watchSlidesProgress: true,
                    observer: true,
                    observeParents: true,
                    navigation: {
                        nextEl: carousel.querySelector('.swiper-button-next'),
                        prevEl: carousel.querySelector('.swiper-button-prev'),
                    },
                    pagination: {
                        el: carousel.querySelector('.swiper-pagination'),
                        clickable: true,
                    },
                    on: {
                        init: function() {
                            this.update();
                        },
                        resize: function() {
                            this.update();
                        }
                    }
                });
            });
        }

        initLightbox() {
            // Create lightbox HTML
            const lightboxHTML = `
                <div class="project-lightbox">
                    <div class="lightbox-content">
                        <span class="lightbox-close">&times;</span>
                        <div class="lightbox-gallery swiper">
                            <div class="swiper-wrapper"></div>
                            <div class="swiper-button-next"></div>
                            <div class="swiper-button-prev"></div>
                            <div class="swiper-pagination"></div>
                        </div>
                        <div class="lightbox-thumbs swiper">
                            <div class="swiper-wrapper"></div>
                        </div>
                    </div>
                </div>
            `;

            // Add lightbox to body
            document.body.insertAdjacentHTML('beforeend', lightboxHTML);

            const lightbox = document.querySelector('.project-lightbox');
            const closeBtn = lightbox.querySelector('.lightbox-close');
            const galleryWrapper = lightbox.querySelector('.lightbox-gallery .swiper-wrapper');
            const thumbsWrapper = lightbox.querySelector('.lightbox-thumbs .swiper-wrapper');
            let lightboxSwiper = null;
            let thumbsSwiper = null;

            // Initialize lightbox Swiper
            const initLightboxSwiper = (gallery) => {
                if (lightboxSwiper) {
                    lightboxSwiper.destroy();
                }
                if (thumbsSwiper) {
                    thumbsSwiper.destroy();
                }

                // Initialize thumbs swiper
                thumbsSwiper = new Swiper('.lightbox-thumbs', {
                    slidesPerView: 'auto',
                    spaceBetween: 10,
                    freeMode: true,
                    watchSlidesProgress: true,
                    centerInsufficientSlides: true,
                    breakpoints: {
                        320: {
                            slidesPerView: 4,
                        },
                        768: {
                            slidesPerView: 6,
                        },
                        1024: {
                            slidesPerView: 8,
                        }
                    }
                });

                // Initialize main swiper
                lightboxSwiper = new Swiper('.lightbox-gallery', {
                    slidesPerView: 1,
                    spaceBetween: 30,
                    navigation: {
                        nextEl: '.lightbox-gallery .swiper-button-next',
                        prevEl: '.lightbox-gallery .swiper-button-prev',
                    },
                    pagination: {
                        el: '.lightbox-gallery .swiper-pagination',
                        clickable: true,
                    },
                    keyboard: {
                        enabled: true,
                    },
                    effect: 'fade',
                    fadeEffect: {
                        crossFade: true
                    },
                    thumbs: {
                        swiper: thumbsSwiper
                    }
                });
            };

            // Handle project click
            document.addEventListener('click', (e) => {
                const projectItem = e.target.closest('.project-item');
                if (!projectItem) return;

                const gallery = JSON.parse(projectItem.dataset.gallery);
                if (!gallery || !gallery.length) return;

                // Populate gallery
                galleryWrapper.innerHTML = gallery.map(image => `
                    <div class="swiper-slide">
                        <img src="${image.url}" alt="${image.alt || ''}">
                    </div>
                `).join('');

                // Populate thumbs
                thumbsWrapper.innerHTML = gallery.map(image => `
                    <div class="swiper-slide">
                        <img src="${image.url}" alt="${image.alt || ''}">
                    </div>
                `).join('');

                // Initialize Swipers and show lightbox
                initLightboxSwiper(gallery);
                lightbox.classList.add('active');
                document.body.style.overflow = 'hidden';
            });

            // Close lightbox
            const closeLightbox = () => {
                lightbox.classList.remove('active');
                document.body.style.overflow = '';
                if (lightboxSwiper) {
                    lightboxSwiper.destroy();
                    lightboxSwiper = null;
                }
                if (thumbsSwiper) {
                    thumbsSwiper.destroy();
                    thumbsSwiper = null;
                }
            };

            closeBtn.addEventListener('click', closeLightbox);
            lightbox.addEventListener('click', (e) => {
                if (e.target === lightbox) {
                    closeLightbox();
                }
            });

            // Close on escape key
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape' && lightbox.classList.contains('active')) {
                    closeLightbox();
                }
            });
        }
    }

    // Initialize when DOM is ready
    $(document).ready(() => {
        new ProjectCarousel();
    });

})(jQuery); 
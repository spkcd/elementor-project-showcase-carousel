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
                try {
                    // Check if required elements exist
                    const nextButton = carousel.querySelector('.swiper-button-next');
                    const prevButton = carousel.querySelector('.swiper-button-prev');
                    const pagination = carousel.querySelector('.swiper-pagination');

                    // Parse settings safely
                    let settings = {};
                    try {
                        settings = JSON.parse(carousel.dataset.swiperSettings || '{}');
                    } catch (e) {
                        console.warn('Invalid Swiper settings:', e);
                    }

                    // Initialize Swiper with safe defaults
                    new Swiper(carousel, {
                        ...settings,
                        loop: true,
                        effect: 'slide',
                        speed: 800,
                        grabCursor: true,
                        watchSlidesProgress: true,
                        observer: true,
                        observeParents: true,
                        navigation: nextButton && prevButton ? {
                            nextEl: nextButton,
                            prevEl: prevButton,
                        } : false,
                        pagination: pagination ? {
                            el: pagination,
                            clickable: true,
                        } : false,
                        on: {
                            init: function() {
                                if (this.update) {
                                    this.update();
                                }
                            },
                            resize: function() {
                                if (this.update) {
                                    setTimeout(() => {
                                        if (this.update) {
                                            this.update();
                                        }
                                    }, 100);
                                }
                            }
                        }
                    });
                } catch (error) {
                    console.error('Error initializing Swiper:', error);
                }
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
                try {
                    // Destroy existing instances
                    if (lightboxSwiper) {
                        lightboxSwiper.destroy(true, true);
                        lightboxSwiper = null;
                    }
                    if (thumbsSwiper) {
                        thumbsSwiper.destroy(true, true);
                        thumbsSwiper = null;
                    }

                    // Initialize thumbs swiper first
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
                } catch (error) {
                    console.error('Error initializing lightbox Swiper:', error);
                }
            };

            // Handle project click
            document.addEventListener('click', (e) => {
                const projectItem = e.target.closest('.project-item');
                if (!projectItem) return;

                try {
                    const gallery = JSON.parse(projectItem.dataset.gallery);
                    if (!gallery || !gallery.length) return;

                    // Add main image to gallery if not already present
                    const mainImage = projectItem.querySelector('.project-image img');
                    if (mainImage && !gallery.some(img => img.url === mainImage.src)) {
                        gallery.unshift({
                            url: mainImage.src,
                            alt: mainImage.alt || ''
                        });
                    }

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
                } catch (error) {
                    console.error('Error handling project click:', error);
                }
            });

            // Close lightbox
            const closeLightbox = () => {
                lightbox.classList.remove('active');
                document.body.style.overflow = '';
                if (lightboxSwiper) {
                    lightboxSwiper.destroy(true, true);
                    lightboxSwiper = null;
                }
                if (thumbsSwiper) {
                    thumbsSwiper.destroy(true, true);
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
        
        // Handle orientation changes for proper mobile layout
        window.addEventListener('orientationchange', () => {
            setTimeout(() => {
                const carousels = document.querySelectorAll('.project-carousel .swiper');
                carousels.forEach(carousel => {
                    if (carousel.swiper && carousel.swiper.update) {
                        carousel.swiper.update();
                    }
                });
            }, 200);
        });

        // Handle View All button click
        $('.view-all-button').on('click', function() {
            const $carousel = $('.project-carousel');
            const $grid = $('.project-grid');
            const $button = $(this);
            
            if ($grid.is(':visible')) {
                // Switch back to carousel
                $grid.hide();
                $carousel.show();
                $button.text('View All');
            } else {
                // Switch to grid
                $carousel.hide();
                $grid.show();
                $button.text('Show Carousel');
            }
        });
    });

})(jQuery); 
/**
 * Modern OneForm Homepage - Animations & Interactions
 */

(function($) {
    'use strict';

    $(document).ready(function() {

        /**
         * Animated Counter for Statistics
         */
        function animateCounters() {
            $('.stat-number').each(function() {
                const $this = $(this);
                const countTo = parseInt($this.attr('data-count'));

                $({ countNum: 0 }).animate({
                    countNum: countTo
                }, {
                    duration: 2000,
                    easing: 'swing',
                    step: function() {
                        $this.text(Math.floor(this.countNum).toLocaleString());
                    },
                    complete: function() {
                        $this.text(this.countNum.toLocaleString());
                    }
                });
            });
        }

        /**
         * Scroll Reveal Animation
         */
        function revealOnScroll() {
            const reveals = $('.step-card, .feature-card, .testimonial-card, .college-card-mini');

            reveals.each(function() {
                const windowHeight = window.innerHeight;
                const elementTop = $(this).offset().top;
                const elementVisible = 150;

                if (elementTop < windowHeight - elementVisible) {
                    $(this).addClass('animate-reveal');
                }
            });
        }

        /**
         * Smooth Scroll for Anchor Links
         */
        $('a[href^="#"]').on('click', function(e) {
            const target = $(this.getAttribute('href'));

            if (target.length) {
                e.preventDefault();
                $('html, body').stop().animate({
                    scrollTop: target.offset().top - 80
                }, 1000);
            }
        });

        /**
         * Video Play Button
         */
        $('.play-button').on('click', function() {
            // Replace with actual video embed
            const videoEmbed = `
                <iframe
                    width="100%"
                    height="100%"
                    src="https://www.youtube.com/embed/YOUR_VIDEO_ID?autoplay=1"
                    frameborder="0"
                    allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture"
                    allowfullscreen
                    style="position: absolute; top: 0; left: 0; width: 100%; height: 100%;">
                </iframe>
            `;

            $(this).closest('.video-embed').html(videoEmbed);
        });

        /**
         * FAQ Accordion (if needed)
         */
        $('.faq-question').on('click', function() {
            $(this).next('.faq-answer').slideToggle(300);
            $(this).toggleClass('active');
        });

        /**
         * Add hover effects to college cards
         */
        $('.college-card-mini').hover(
            function() {
                $(this).find('.college-type-badge').css('background', '#667eea').css('color', '#fff');
            },
            function() {
                $(this).find('.college-type-badge').css('background', '#e6f2ff').css('color', '#667eea');
            }
        );

        /**
         * Parallax effect for hero section
         */
        $(window).on('scroll', function() {
            const scrolled = $(this).scrollTop();
            $('.hero-illustration').css('transform', 'translateY(' + (scrolled * 0.3) + 'px)');
        });

        /**
         * Initialize on scroll
         */
        $(window).on('scroll', revealOnScroll);

        /**
         * Initialize counters when visible
         */
        let counterAnimated = false;
        $(window).on('scroll', function() {
            if (!counterAnimated) {
                const heroStats = $('.hero-stats');
                if (heroStats.length) {
                    const windowHeight = window.innerHeight;
                    const elementTop = heroStats.offset().top;

                    if (elementTop < windowHeight) {
                        animateCounters();
                        counterAnimated = true;
                    }
                }
            }
        });

        /**
         * Add CSS class for reveal animation
         */
        const style = document.createElement('style');
        style.textContent = `
            .step-card,
            .feature-card,
            .testimonial-card,
            .college-card-mini {
                opacity: 0;
                transform: translateY(30px);
                transition: all 0.6s ease-out;
            }

            .animate-reveal {
                opacity: 1 !important;
                transform: translateY(0) !important;
            }
        `;
        document.head.appendChild(style);

        // Initial check for visible elements
        revealOnScroll();

        /**
         * Mobile Menu (if needed)
         */
        $('.mobile-menu-toggle').on('click', function() {
            $('.mobile-menu').slideToggle(300);
        });

        /**
         * Testimonials Auto-rotate (optional)
         */
        let currentTestimonial = 0;
        const testimonials = $('.testimonial-card');

        function rotateTestimonials() {
            testimonials.removeClass('active-testimonial');
            testimonials.eq(currentTestimonial).addClass('active-testimonial');
            currentTestimonial = (currentTestimonial + 1) % testimonials.length;
        }

        // Uncomment to enable auto-rotate
        // setInterval(rotateTestimonials, 5000);

        /**
         * Add "Back to Top" button
         */
        const backToTop = $('<button class="back-to-top">↑</button>');
        backToTop.css({
            position: 'fixed',
            bottom: '30px',
            right: '30px',
            width: '50px',
            height: '50px',
            background: '#667eea',
            color: '#fff',
            border: 'none',
            borderRadius: '50%',
            fontSize: '24px',
            cursor: 'pointer',
            display: 'none',
            zIndex: '1000',
            boxShadow: '0 4px 12px rgba(0,0,0,0.2)',
            transition: 'all 0.3s ease'
        });

        $('body').append(backToTop);

        $(window).on('scroll', function() {
            if ($(this).scrollTop() > 500) {
                backToTop.fadeIn();
            } else {
                backToTop.fadeOut();
            }
        });

        backToTop.on('click', function() {
            $('html, body').animate({ scrollTop: 0 }, 800);
        });

        backToTop.hover(
            function() {
                $(this).css({ transform: 'scale(1.1)', background: '#5568d3' });
            },
            function() {
                $(this).css({ transform: 'scale(1)', background: '#667eea' });
            }
        );

        /**
         * Form field focus effects
         */
        $('input, textarea, select').on('focus', function() {
            $(this).parent().addClass('field-focused');
        }).on('blur', function() {
            $(this).parent().removeClass('field-focused');
        });

        /**
         * Loading animation for buttons
         */
        $('.btn-submit').on('click', function() {
            const $btn = $(this);
            const originalText = $btn.text();

            $btn.html('<span class="spinner"></span> Processing...').prop('disabled', true);

            // Restore after form submission (handled by form handler)
            setTimeout(function() {
                $btn.html(originalText).prop('disabled', false);
            }, 3000);
        });

        /**
         * Lazy load images
         */
        if ('IntersectionObserver' in window) {
            const imageObserver = new IntersectionObserver(function(entries) {
                entries.forEach(function(entry) {
                    if (entry.isIntersecting) {
                        const img = entry.target;
                        img.src = img.dataset.src;
                        img.classList.remove('lazy');
                        imageObserver.unobserve(img);
                    }
                });
            });

            document.querySelectorAll('img.lazy').forEach(function(img) {
                imageObserver.observe(img);
            });
        }

        console.log('OneForm Modern Homepage Initialized ✨');
    });

})(jQuery);


document.addEventListener("DOMContentLoaded", () => {

    /* =====================================
       1. GOLD PARTICLES
    ===================================== */

    const particlesContainer = document.querySelector(".hero-particles");

    if (particlesContainer &&
        !window.matchMedia("(prefers-reduced-motion: reduce)").matches) {

        const particleCount = window.innerWidth < 600 ? 18 : 38;

        for (let i = 0; i < particleCount; i++) {
            const particle = document.createElement("i");

            particle.style.left = Math.random() * 100 + "%";
            particle.style.animationDuration = (7 + Math.random() * 12) + "s";
            particle.style.animationDelay = (Math.random() * 10) + "s";
            particle.style.opacity = Math.random() * 0.7;

            const size = 2 + Math.random() * 3;
            particle.style.width = size + "px";
            particle.style.height = size + "px";

            particlesContainer.appendChild(particle);
        }
    }


    /* =====================================
       2. SCROLL REVEAL
    ===================================== */

    const revealElements = document.querySelectorAll(".reveal");

    const revealObserver = new IntersectionObserver((entries, observer) => {

        entries.forEach(entry => {

            if (entry.isIntersecting) {
                entry.target.classList.add("visible");
                observer.unobserve(entry.target);
            }

        });

    }, {
        threshold: 0.12,
        rootMargin: "0px 0px -40px 0px"
    });

    revealElements.forEach(element => {
        revealObserver.observe(element);
    });


    /* =====================================
       3. ANIMATED COUNTERS
    ===================================== */

    const counters = document.querySelectorAll(".counter");

    const animateCounter = (counter) => {

        const target = Number(counter.dataset.target);
        const suffix = counter.dataset.suffix || "";
        const duration = 1800;
        const startTime = performance.now();

        function updateCounter(currentTime) {

            const progress = Math.min(
                (currentTime - startTime) / duration,
                1
            );

            const ease = 1 - Math.pow(1 - progress, 4);

            const currentValue = Math.floor(target * ease);

            counter.textContent = currentValue.toLocaleString() + suffix;

            if (progress < 1) {
                requestAnimationFrame(updateCounter);
            } else {
                counter.textContent = target.toLocaleString() + suffix;
            }

        }

        requestAnimationFrame(updateCounter);
    };


    const counterObserver = new IntersectionObserver((entries, observer) => {

        entries.forEach(entry => {

            if (entry.isIntersecting) {
                animateCounter(entry.target);
                observer.unobserve(entry.target);
            }

        });

    }, {
        threshold: 0.6
    });

    counters.forEach(counter => {
        counterObserver.observe(counter);
    });


    /* =====================================
       4. CATEGORY 3D TILT
    ===================================== */

    const categoryCards = document.querySelectorAll(".category-card");

    const canTilt = window.matchMedia(
        "(hover: hover) and (pointer: fine)"
    ).matches;

    if (canTilt) {

        categoryCards.forEach(card => {

            card.addEventListener("mousemove", event => {

                const rect = card.getBoundingClientRect();

                const x = event.clientX - rect.left;
                const y = event.clientY - rect.top;

                const centerX = rect.width / 2;
                const centerY = rect.height / 2;

                const rotateY = ((x - centerX) / centerX) * 5;
                const rotateX = ((centerY - y) / centerY) * 5;

                card.style.transform =
                    `perspective(1000px) rotateX(${rotateX}deg) rotateY(${rotateY}deg) translateY(-5px)`;

            });

            card.addEventListener("mouseleave", () => {

                card.style.transform =
                    "perspective(1000px) rotateX(0) rotateY(0) translateY(0)";

            });

        });

    }


    /* =====================================
       5. SECTION HEADING ANIMATION
    ===================================== */

    const headings = document.querySelectorAll(".section-heading");

    const headingObserver = new IntersectionObserver((entries, observer) => {

        entries.forEach(entry => {

            if (entry.isIntersecting) {
                entry.target.classList.add("visible");
                observer.unobserve(entry.target);
            }

        });

    }, {
        threshold: 0.5
    });

    headings.forEach(heading => {
        headingObserver.observe(heading);
    });


    /* =====================================
       6. PRODUCT CARD STAGGER
    ===================================== */

    document.querySelectorAll(".product-card").forEach((card, index) => {

        card.style.setProperty("--i", index % 4);

    });


    /* =====================================
       7. SMOOTH SCROLL
    ===================================== */

    document.querySelectorAll('a[href^="#"]').forEach(link => {

        link.addEventListener("click", event => {

            const targetId = link.getAttribute("href");

            if (!targetId || targetId === "#") return;

            const target = document.querySelector(targetId);

            if (target) {

                event.preventDefault();

                target.scrollIntoView({
                    behavior: "smooth",
                    block: "start"
                });

            }

        });

    });


    /* =====================================
       8. PARALLAX HERO
    ===================================== */

    const hero = document.querySelector(".hero-section");
    const heroGlow = document.querySelector(".hero-glow-1");

    if (hero && heroGlow && canTilt) {

        hero.addEventListener("mousemove", event => {

            const rect = hero.getBoundingClientRect();

            const x = (event.clientX - rect.left) / rect.width - 0.5;
            const y = (event.clientY - rect.top) / rect.height - 0.5;

            heroGlow.style.transform =
                `translate(${x * -35}px, ${y * -25}px)`;

        });

        hero.addEventListener("mouseleave", () => {
            heroGlow.style.transform = "";
        });

    }

});

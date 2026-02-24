/* ================= EFEITO DE DIGITAÇÃO NO INPUT ================= */
const textos = [
    "Lugares para visitar?",
    "Quais as melhores cachoeiras?",
    "Melhores lugares para comer?",
    "Quais são as comidas típicas?"
];

const input = document.getElementById("searchInput");

if (input) {
    let textoIndex = 0;
    let letraIndex = 0;
    let apagando = false;

    function typeEffect() {
        const textoAtual = textos[textoIndex];

        if (!apagando) {
            input.placeholder = textoAtual.slice(0, letraIndex++);
            if (letraIndex > textoAtual.length) {
                apagando = true;
                setTimeout(typeEffect, 1500);
                return;
            }
        } else {
            input.placeholder = textoAtual.slice(0, letraIndex--);
            if (letraIndex < 0) {
                apagando = false;
                textoIndex = (textoIndex + 1) % textos.length;
                letraIndex = 0;
            }
        }

        setTimeout(typeEffect, apagando ? 50 : 100);
    }

    typeEffect();
}

/* ================= SCROLL HORIZONTAL COM INÉRCIA ================= */
const container = document.querySelector(".cachoeira");

if (container) {
    let isDown = false;
    let startX = 0;
    let scrollLeft = 0;
    let velocity = 0;
    let momentumID = null;

    const startDrag = (x, target) => {
        if (target.closest(".card")) return; // NÃO arrasta sobre card

        isDown = true;
        startX = x - container.offsetLeft;
        scrollLeft = container.scrollLeft;
        container.style.cursor = "grabbing";
        cancelMomentum();
    };

    const drag = (x) => {
        if (!isDown) return;

        const walk = (x - startX) * 1.5;
        velocity = walk - (scrollLeft - container.scrollLeft);
        container.scrollLeft = scrollLeft - walk;
    };

    const stopDrag = () => {
        if (!isDown) return;

        isDown = false;
        container.style.cursor = "grab";
        momentum();
    };

    const momentum = () => {
        cancelMomentum();
        momentumID = requestAnimationFrame(() => {
            container.scrollLeft -= velocity;
            velocity *= 0.95;
            if (Math.abs(velocity) > 0.5) momentum();
        });
    };

    const cancelMomentum = () => {
        if (momentumID) cancelAnimationFrame(momentumID);
    };

    /* Eventos desktop */
    container.addEventListener("mousedown", e => startDrag(e.pageX, e.target));
    container.addEventListener("mousemove", e => drag(e.pageX));
    container.addEventListener("mouseup", stopDrag);
    container.addEventListener("mouseleave", stopDrag);

    /* Eventos touch */
    container.addEventListener("touchstart", e => startDrag(e.touches[0].pageX, e.target));
    container.addEventListener("touchmove", e => drag(e.touches[0].pageX));
    container.addEventListener("touchend", stopDrag);
}

/* ================= ANIMAÇÕES GSAP ================= */
gsap.registerPlugin(ScrollTrigger);

/* Header entra do topo */
gsap.from(".header", {
    y: -100,
    opacity: 0,
    duration: 1.5,
    ease: "power3.out"
});

/* Hero fade-in */
gsap.from(".hero", {
    opacity: 0,
    duration: 2,
    ease: "power2.out"
});

/* Texto da seção Cachoeira */
gsap.from("#titulo h1, #titulo h3, #titulo p", {
    y: 50,
    opacity: 0,
    duration: 1.2,
    stagger: 0.3,
    ease: "power2.out",
    scrollTrigger: {
        trigger: "#titulo",
        start: "top 90%",
        toggleActions: "play none none reverse"
    }
});

/* Cards da seção Cachoeira */
gsap.from("#card-turismo .card", {
    y: 30,
    opacity: 0,
    duration: 1,
    stagger: 0.2,
    ease: "power2.out",
    scrollTrigger: {
        trigger: "#card-turismo",
        start: "top 80%",
        toggleActions: "play none none reverse"
    }
});

/* Hotéis */
gsap.from(".hotel-cards img", {
    y: 50,
    opacity: 0,
    duration: 1.2,
    stagger: 0.3,
    ease: "power2.out",
    scrollTrigger: {
        trigger: ".hoteis",
        start: "top 80%",
        toggleActions: "play none none reverse"
    }
});

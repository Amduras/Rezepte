import './bootstrap';

document.addEventListener('DOMContentLoaded', () => {
    const track = document.getElementById('carousel-track');
    const prevBtn = document.getElementById('carousel-prev');
    const nextBtn = document.getElementById('carousel-next');
    const dots = document.querySelectorAll('.carousel-dot');
    const servLeft = document.getElementById('servings-left')
    const servRight = document.getElementById('servings-right')

    if (!track || !prevBtn || !nextBtn) return;

    const allItems = Array.from(track.querySelectorAll('.carousel-item'));
    const totalItems = allItems.length;
    const originalCount = totalItems - 6;

    if (originalCount < 3) return;

    let currentIndex = 3;
    let isTransitioning = false;
    const TRANSITION_DURATION = 500;

    function updateCarousel(animate = true) {
        if (animate) {
            track.style.transition = `transform ${TRANSITION_DURATION}ms cubic-bezier(0.25, 1, 0.5, 1)`;
        } else {
            track.style.transition = 'none';
        }

        const containerWidth = track.parentElement.offsetWidth;
        const itemWidth = allItems[0].offsetWidth;
        const offset = (containerWidth / 2) - (itemWidth / 2) - (currentIndex * itemWidth);

        track.style.transform = `translate3d(${offset}px, 0, 0)`;

        updateVisuals();
        updateDots();
    }

    function updateVisuals() {
        allItems.forEach((item, index) => {
            if (index === currentIndex) {
                item.style.transform = 'translateZ(0) scale(1)';
                item.style.opacity = '1';
                item.style.zIndex = '10';
            } else if (index === currentIndex - 1 || index === currentIndex + 1) {
                item.style.transform = 'translateZ(0) scale(0.85)';
                item.style.opacity = '0.5';
                item.style.zIndex = '5';
            } else {
                item.style.transform = 'translateZ(0) scale(0.7)';
                item.style.opacity = '0.2';
                item.style.zIndex = '0';
            }
        });
    }

    function updateDots() {
        let realIndex = currentIndex - 3;
        if (realIndex < 0) realIndex += originalCount;
        if (realIndex >= originalCount) realIndex -= originalCount;

        dots.forEach((dot, index) => {
            if (index === realIndex) {
                dot.classList.remove('bg-zinc-700', 'w-2');
                dot.classList.add('bg-white', 'w-6');
            } else {
                dot.classList.remove('bg-white', 'w-6');
                dot.classList.add('bg-zinc-700', 'w-2');
            }
        });
    }

    function checkAndResetPosition() {
        let needsReset = false;
        let targetIndex = currentIndex;

        if (currentIndex >= totalItems - 3) {
            targetIndex = currentIndex - originalCount;
            needsReset = true;
        } else if (currentIndex < 3) {
            targetIndex = currentIndex + originalCount;
            needsReset = true;
        }

        if (needsReset) {
            // 1. Klasse hinzufügen: Deaktiviert SOFORT alle Transitionen der Kind-Elemente
            track.classList.add('is-resetting');
            track.style.transition = 'none';

            // 2. Index aktualisieren
            currentIndex = targetIndex;

            // 3. Visuellen Zustand SOFORT anwenden (dank .is-resetting passiert das ohne Animation)
            updateVisuals();
            updateDots();

            // 4. Neue Position berechnen
            const containerWidth = track.parentElement.offsetWidth;
            const itemWidth = allItems[0].offsetWidth;
            const offset = (containerWidth / 2) - (itemWidth / 2) - (currentIndex * itemWidth);

            // 5. Position anwenden
            track.style.transform = `translate3d(${offset}px, 0, 0)`;

            // 6. HARTEN REPAINT ERZWINGEN (Flush Render Queue)
            void track.offsetWidth;

            // 7. Im nächsten Frame die Reset-Klasse entfernen und Transition wieder aktivieren
            requestAnimationFrame(() => {
                track.classList.remove('is-resetting');

                // Noch ein Frame warten, damit der Browser das Entfernen der Klasse registriert
                requestAnimationFrame(() => {
                    track.style.transition = `transform ${TRANSITION_DURATION}ms cubic-bezier(0.25, 1, 0.5, 1)`;
                    isTransitioning = false;
                });
            });
        } else {
            isTransitioning = false;
        }
    }

    function goToNext() {
        if (isTransitioning) return;
        isTransitioning = true;
        currentIndex++;
        updateCarousel(true);
        setTimeout(checkAndResetPosition, TRANSITION_DURATION);
    }

    function goToPrev() {
        if (isTransitioning) return;
        isTransitioning = true;
        currentIndex--;
        updateCarousel(true);
        setTimeout(checkAndResetPosition, TRANSITION_DURATION);
    }

    nextBtn.addEventListener('click', goToNext);
    prevBtn.addEventListener('click', goToPrev);

    dots.forEach((dot, index) => {
        dot.addEventListener('click', () => {
            if (isTransitioning) return;
            isTransitioning = true;
            currentIndex = index + 3;
            updateCarousel(true);
            setTimeout(checkAndResetPosition, TRANSITION_DURATION);
        });
    });

    document.addEventListener('keydown', (e) => {
        if (e.key === 'ArrowLeft') goToPrev();
        if (e.key === 'ArrowRight') goToNext();
    });

    window.addEventListener('resize', () => {
        updateCarousel(false);
    });

    let touchStartX = 0;
    let touchEndX = 0;

    track.addEventListener('touchstart', (e) => {
        touchStartX = e.changedTouches[0].screenX;
    }, { passive: true });

    track.addEventListener('touchend', (e) => {
        touchEndX = e.changedTouches[0].screenX;
        const diff = touchStartX - touchEndX;
        if (Math.abs(diff) > 50) {
            diff > 0 ? goToNext() : goToPrev();
        }
    }, { passive: true });

    // Initiale Positionierung
    updateCarousel(false);

    servLeft.addEventListener('click', (e) => {
        let servings = document.getElementById('servings-amount');
        let amount;
        if ('servings' in localStorage) {
            amount = localStorage.getItem('servings');
        } else {
            console.log('else')
            amount = document.getElementById('servings-amount').innerHTML;
        }
        let list = Array.from(document.getElementById('ingredients-list').children);
        let scaled = scaleIngredients(list, amount, amount - 1);
        if (amount - 1 > 0) {
            updateIngredientsInPlace(scaled, '#ingredients-list');
            servings.textContent = amount - 1;
            localStorage.setItem('servings', amount - 1);
        }
    })

    servRight.addEventListener('click', () => {
        let servings = document.getElementById('servings-amount');
        let amount;
        if ('servings' in localStorage) {
            amount = localStorage.getItem('servings');
        } else {
            amount = document.getElementById('servings-amount').innerHTML;
        }
        let list = Array.from(document.getElementById('ingredients-list').children);
        let scaled = scaleIngredients(list, amount, parseInt(amount) + 1);
        updateIngredientsInPlace(scaled, '#ingredients-list');
        servings.textContent = parseInt(amount) + 1;
        localStorage.setItem('servings', parseInt(amount) + 1);
    })

    mobile_ingredients();
});

function mobile_ingredients() {
    const sectionRight = document.getElementById('section-right');
    const showBtn = document.getElementById('ingredients__show');
    const closeBtn = document.getElementById('ingredients__close');

    function openMobileMenu() {
        // 'hidden' entfernen, damit es sichtbar wird
        sectionRight.classList.remove('hidden');
        // 'fixed inset-0' macht es zu einem Vollbild-Overlay über allem anderen
        sectionRight.classList.add('fixed', 'inset-0', 'z-50', 'overflow-y-auto');

        showBtn.classList.add('hidden');
        closeBtn.classList.remove('hidden');
    }

    function closeMobileMenu() {
        // Overlay-Klassen entfernen
        sectionRight.classList.remove('fixed', 'inset-0', 'z-50', 'overflow-y-auto');
        // Auf Mobile wieder verstecken (auf Desktop greift weiterhin md:block)
        sectionRight.classList.add('hidden');

        closeBtn.classList.add('hidden');
        showBtn.classList.remove('hidden');
    }

    showBtn.addEventListener('click', () => {
        // Nur ausführen, wenn der Bildschirm schmaler als 768px ist (Mobile)
        if (window.innerWidth < 768) {
            openMobileMenu();
        }
    });

    closeBtn.addEventListener('click', () => {
        if (window.innerWidth < 768) {
            closeMobileMenu();
        }
    });

    // Optional, aber empfohlen: Falls der User das Browserfenster aufzieht,
    // während das Menü offen ist, räumen wir die Klassen auf,
    // damit das Desktop-Layout nicht zerschossen wird.
    window.addEventListener('resize', () => {
        if (window.innerWidth >= 768) {
            sectionRight.classList.remove('fixed', 'inset-0', 'z-50', 'overflow-y-auto');
            closeBtn.classList.add('hidden');
            showBtn.classList.remove('hidden');
        }
    });
}

window.addEventListener('load', function() {
    if ('servings' in localStorage) {
        let servings = document.getElementById('servings-amount');
        let oldAmount = document.getElementById('servings-amount').innerHTML;
        let list = Array.from(document.getElementById('ingredients-list').children);
        let amount = localStorage.getItem('servings');
        let scaled = scaleIngredients(list, oldAmount, parseInt(amount));
        updateIngredientsInPlace(scaled, '#ingredients-list');
        servings.textContent = amount;
    }
    document.getElementById('ingredients-list').classList.remove('invisible');
})

window.addEventListener('click', function(e) {
});

function parseIngredient(input) {
    let text = "";

    if (input instanceof HTMLElement || input instanceof Element) {
        text = input.innerText || input.textContent || "";
    }
    else if (typeof input === 'object' && input !== null) {
        text = input.text || input.ingredient || input.name || String(input);
    }
    else {
        text = String(input);
    }

    text = text.trim();

    const regex = /^(\d+(?:[.,]\d+)?|\d+\/\d+)\s*([a-zA-ZäöüÄÖÜß\/]*)\s*(.*)$/i;
    const match = text.match(regex);

    if (match) {
        let qtyStr = match[1].replace(',', '.');
        let qty = evaluateFraction(qtyStr);
        let unit = match[2] || "";
        let ingredient = match[3] || "";

        return { qty, unit, ingredient, original: text, isParsed: true };
    }

    return { qty: 1, unit: "", ingredient: text, original: text, isParsed: false };
}

function scaleIngredients(ingredientTexts, originalPortions, newPortions) {
    // Sicherstellen, dass es ein Array ist
    if (!Array.isArray(ingredientTexts)) {
        console.error("Es wurde kein Array übergeben!");
        return [];
    }

    const factor = newPortions / originalPortions;

    return ingredientTexts.map(item => {
        const parsed = parseIngredient(item); // item kann jetzt alles sein

        if (parsed.isParsed) {
            const newQty = parsed.qty * factor;
            return {
                ...parsed,
                newQty: newQty,
                displayText: `${formatQuantity(newQty)}${parsed.unit ? ' ' + parsed.unit : ''} ${parsed.ingredient}`
            };
        } else {
            return { ...parsed, displayText: parsed.ingredient };
        }
    });
}

// --- Hilfsfunktionen ---
function evaluateFraction(str) {
    if (str.includes('/')) {
        const parts = str.split('/');
        return parseFloat(parts[0]) / parseFloat(parts[1]);
    }
    return parseFloat(str);
}

function formatQuantity(num) {
    if (Number.isInteger(num)) return num.toString();
    return parseFloat(num.toFixed(1)).toString().replace('.', ',');
}

function updateIngredientsInPlace(scaledData, ulSelector) {
    const liElements = document.querySelectorAll(`${ulSelector} li`);

    if (liElements.length === 0) {
        console.warn(`Keine <li> Elemente gefunden für den Selektor: ${ulSelector}`);
        return;
    }

    liElements.forEach((li, index) => {
        if (scaledData[index]) {
            li.textContent = scaledData[index].displayText;
        } else {
            console.warn(`Keine Daten für Index ${index}. Element bleibt unverändert:`, li.textContent);
        }
    });
}

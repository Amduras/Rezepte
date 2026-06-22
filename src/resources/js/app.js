import './bootstrap';

// =============================================
// 🚀 HAUPT-ENTRY: DOM-Ready
// =============================================
document.addEventListener('DOMContentLoaded', () => {

    // Detailseite: Portionen-Skalierung + Mobile Zutaten
    if (document.getElementById('servings-amount') &&
        document.getElementById('ingredients-list')) {
        servingsScaler();
        mobileIngredients();
    }

    // Detailseite: Endloses Carousel (nur bei 3+ Bildern)
    if (document.getElementById('carousel-track')) {
        recipeCarousel();
    }

    // Startseite: Daily-Carousel
    if (document.getElementById('daily-carousel')) {
        dailyCarousel();
    }

    // Create/Edit: Zutaten dynamisch
    if (document.getElementById('ingredients-list') &&
        document.getElementById('add-ingredient-btn')) {
        addIngredient();
    }

    // Create/Edit: Schritte dynamisch
    if (document.getElementById('steps-list') &&
        document.getElementById('add-step-btn')) {
        addStep();
    }

    // Create/Edit: Bild-Vorschau beim Upload
    if (document.getElementById('dropzone-file')) {
        imagePreview();
    }
});

// =============================================
// 🎠 DETAILSEITE: Endloses Carousel
// =============================================
function recipeCarousel() {
    const track = document.getElementById('carousel-track');
    const prevBtn = document.getElementById('carousel-prev');
    const nextBtn = document.getElementById('carousel-next');
    const dots = document.querySelectorAll('.carousel-dot');

    if (!track || !prevBtn || !nextBtn) return;

    const allItems = Array.from(track.querySelectorAll('.carousel-item'));
    const totalItems = allItems.length;
    const originalCount = totalItems - 6; // 3 Klone vorne + 3 hinten

    if (originalCount < 3) return; // Carousel braucht mindestens 3 Bilder

    let currentIndex = 3; // Start bei erstem echten Bild (nach den 3 Klonen)
    let isTransitioning = false;
    const TRANSITION_DURATION = 500;

    function updateCarousel(animate = true) {
        track.style.transition = animate
            ? `transform ${TRANSITION_DURATION}ms cubic-bezier(0.25, 1, 0.5, 1)`
            : 'none';

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
            track.style.transition = 'none';
            currentIndex = targetIndex;
            updateVisuals();
            updateDots();

            const containerWidth = track.parentElement.offsetWidth;
            const itemWidth = allItems[0].offsetWidth;
            const offset = (containerWidth / 2) - (itemWidth / 2) - (currentIndex * itemWidth);
            track.style.transform = `translate3d(${offset}px, 0, 0)`;

            // Force Reflow
            void track.offsetWidth;

            requestAnimationFrame(() => {
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

    // Keyboard-Navigation
    document.addEventListener('keydown', (e) => {
        if (e.key === 'ArrowLeft') goToPrev();
        if (e.key === 'ArrowRight') goToNext();
    });

    // Touch-Support
    let touchStartX = 0;
    track.addEventListener('touchstart', (e) => {
        touchStartX = e.changedTouches[0].screenX;
    }, { passive: true });

    track.addEventListener('touchend', (e) => {
        const diff = touchStartX - e.changedTouches[0].screenX;
        if (Math.abs(diff) > 50) {
            diff > 0 ? goToNext() : goToPrev();
        }
    }, { passive: true });

    // Resize
    window.addEventListener('resize', () => updateCarousel(false));

    // Init
    updateCarousel(false);
}

// =============================================
// 🎲 STARTSEITE: Daily-Carousel (Auto-Play)
// =============================================
function dailyCarousel() {
    const carousel = document.getElementById('daily-carousel');
    const prevBtn = document.getElementById('daily-prev');
    const nextBtn = document.getElementById('daily-next');
    const dots = document.querySelectorAll('.daily-dot');
    const slides = carousel.querySelectorAll('.daily-slide');

    if (!carousel || slides.length === 0) return;

    let currentIndex = 0;
    const totalSlides = slides.length;
    let autoPlayInterval = null;

    function update() {
        // Auf Mobile: 1 Slide, Tablet: 2, Desktop: 3
        const slideWidth = slides[0].offsetWidth;
        carousel.style.transform = `translateX(-${currentIndex * slideWidth}px)`;

        dots.forEach((dot, index) => {
            if (index === currentIndex) {
                dot.classList.add('bg-emerald-500', 'w-6');
                dot.classList.remove('bg-stone-600');
            } else {
                dot.classList.remove('bg-emerald-500', 'w-6');
                dot.classList.add('bg-stone-600');
            }
        });
    }

    function goToNext() {
        currentIndex = (currentIndex + 1) % totalSlides;
        update();
    }

    function goToPrev() {
        currentIndex = (currentIndex - 1 + totalSlides) % totalSlides;
        update();
    }

    function startAutoPlay() {
        stopAutoPlay();
        autoPlayInterval = setInterval(goToNext, 5000);
    }

    function stopAutoPlay() {
        if (autoPlayInterval) {
            clearInterval(autoPlayInterval);
            autoPlayInterval = null;
        }
    }

    // Event Listener
    nextBtn?.addEventListener('click', () => {
        goToNext();
        startAutoPlay(); // Reset Timer nach manueller Interaktion
    });

    prevBtn?.addEventListener('click', () => {
        goToPrev();
        startAutoPlay();
    });

    dots.forEach((dot, index) => {
        dot.addEventListener('click', () => {
            currentIndex = index;
            update();
            startAutoPlay();
        });
    });

    // Pause bei Hover
    carousel.parentElement.addEventListener('mouseenter', stopAutoPlay);
    carousel.parentElement.addEventListener('mouseleave', startAutoPlay);

    // Touch-Support
    let touchStartX = 0;
    carousel.addEventListener('touchstart', (e) => {
        touchStartX = e.changedTouches[0].screenX;
        stopAutoPlay();
    }, { passive: true });

    carousel.addEventListener('touchend', (e) => {
        const diff = touchStartX - e.changedTouches[0].screenX;
        if (Math.abs(diff) > 50) {
            diff > 0 ? goToNext() : goToPrev();
        }
        startAutoPlay();
    }, { passive: true });

    // Resize
    window.addEventListener('resize', update);

    // Init
    update();
    startAutoPlay();
}

// =============================================
// 📱 MOBILE: Zutaten-Menü Toggle
// =============================================
function mobileIngredients() {
    const sectionRight = document.getElementById('section-right');
    const showBtn = document.getElementById('ingredients__show');
    const closeBtn = document.getElementById('ingredients__close');

    if (!sectionRight || !showBtn || !closeBtn) return;

    function openMobileMenu() {
        sectionRight.classList.remove('hidden');
        sectionRight.classList.add('fixed', 'inset-0', 'z-50', 'overflow-y-auto');
        showBtn.classList.add('hidden');
        closeBtn.classList.remove('hidden');
    }

    function closeMobileMenu() {
        sectionRight.classList.remove('fixed', 'inset-0', 'z-50', 'overflow-y-auto');
        sectionRight.classList.add('hidden');
        closeBtn.classList.add('hidden');
        showBtn.classList.remove('hidden');
    }

    showBtn.addEventListener('click', () => {
        if (window.innerWidth < 768) openMobileMenu();
    });

    closeBtn.addEventListener('click', () => {
        if (window.innerWidth < 768) closeMobileMenu();
    });

    window.addEventListener('resize', () => {
        if (window.innerWidth >= 768) {
            sectionRight.classList.remove('fixed', 'inset-0', 'z-50', 'overflow-y-auto');
            closeBtn.classList.add('hidden');
            showBtn.classList.remove('hidden');
        }
    });
}

// =============================================
// 🍽️ PORTIONEN-SKALIERUNG (überarbeitet)
// =============================================
function servingsScaler() {
    const servLeft = document.getElementById('servings-left');
    const servRight = document.getElementById('servings-right');
    const servingsEl = document.getElementById('servings-amount');
    const ingredientsList = document.getElementById('ingredients-list');

    if (!servLeft || !servRight || !servingsEl || !ingredientsList) return;

    const baseServings = parseInt(ingredientsList.dataset.baseServings) || 1;

    function getCurrentServings() {
        return parseInt(servingsEl.textContent) || baseServings;
    }

    function formatQuantity(num) {
        if (Number.isInteger(num)) return num.toString();
        return parseFloat(num.toFixed(1)).toString().replace('.', ',');
    }

    function updateDisplay() {
        const currentServings = getCurrentServings();
        const factor = currentServings / baseServings;

        ingredientsList.querySelectorAll('li').forEach(li => {
            const originalQty = parseFloat(li.dataset.quantity?.replace(',', '.'));

            // Wenn keine Menge vorhanden oder keine Zahl → überspringen
            if (isNaN(originalQty)) return;

            const newQty = originalQty * factor;
            const qtySpan = li.querySelector('.qty');

            if (qtySpan) {
                qtySpan.textContent = formatQuantity(newQty);
            }
        });
    }

    servLeft.addEventListener('click', () => {
        const amount = getCurrentServings();
        if (amount - 1 <= 0) return;
        servingsEl.textContent = amount - 1;
        updateDisplay();
    });

    servRight.addEventListener('click', () => {
        const amount = getCurrentServings();
        servingsEl.textContent = amount + 1;
        updateDisplay();
    });
}

// =============================================
// ➕ ZUTATEN HINZUFÜGEN/ENTFERNEN
// =============================================
function addIngredient() {
    const ingredientsList = document.getElementById('ingredients-list');
    const addIngredientBtn = document.getElementById('add-ingredient-btn');

    if (!ingredientsList || !addIngredientBtn) return;

    let ingredientIndex = ingredientsList.querySelectorAll('.ingredient-row').length;

    addIngredientBtn.addEventListener('click', () => {
        const row = document.createElement('div');
        row.className = 'ingredient-row w-full md:flex md:items-end gap-4 relative group';
        row.dataset.index = ingredientIndex;

        row.innerHTML = `
            <div class="w-full md:w-5/12">
                <input type="text" name="ingredients[${ingredientIndex}][name]" placeholder="z.B. Mehl" class="block w-full min-w-0 grow bg-transparent py-1.5 pr-3 pl-1 text-base text-stone-400 placeholder:text-gray-500 sm:text-sm/6 rounded-md outline-1 outline-offset-1 outline-white/10 focus-within:outline-2 focus-within:-outline-offset-2 focus-within:outline-emerald-500/50">
            </div>
            <div class="w-full md:w-2/12">
                <input type="text" name="ingredients[${ingredientIndex}][quantity]" placeholder="z.B. 200" class="block w-full min-w-0 grow bg-transparent py-1.5 pr-3 pl-1 text-base text-stone-400 placeholder:text-gray-500 sm:text-sm/6 rounded-md outline-1 outline-offset-1 outline-white/10 focus-within:outline-2 focus-within:-outline-offset-2 focus-within:outline-emerald-500/50">
            </div>
            <div class="w-full md:w-2/12">
                <input type="text" name="ingredients[${ingredientIndex}][unit]" placeholder="g, ml, Stk" class="block w-full min-w-0 grow bg-transparent py-1.5 pr-3 pl-1 text-base text-stone-400 placeholder:text-gray-500 sm:text-sm/6 rounded-md outline-1 outline-offset-1 outline-white/10 focus-within:outline-2 focus-within:-outline-offset-2 focus-within:outline-emerald-500/50">
            </div>
            <div class="w-full md:w-2/12">
                <input type="text" name="ingredients[${ingredientIndex}][note]" placeholder="z.B. gesiebt" class="block w-full min-w-0 grow bg-transparent py-1.5 pr-3 pl-1 text-base text-stone-400 placeholder:text-gray-500 sm:text-sm/6 rounded-md outline-1 outline-offset-1 outline-white/10 focus-within:outline-2 focus-within:-outline-offset-2 focus-within:outline-emerald-500/50">
            </div>
            <button type="button" class="remove-ingredient-btn w-full md:w-1/12 flex items-center justify-center text-red-400 hover:text-red-300 transition-colors py-1.5 cursor-pointer">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
            </button>
        `;

        ingredientsList.appendChild(row);
        ingredientIndex++;
        updateRemoveButtons();
    });

    ingredientsList.addEventListener('click', (e) => {
        const removeBtn = e.target.closest('.remove-ingredient-btn');
        if (!removeBtn) return;

        const row = removeBtn.closest('.ingredient-row');
        if (!row) return;

        if (ingredientsList.querySelectorAll('.ingredient-row').length <= 1) {
            row.querySelectorAll('input').forEach(input => input.value = '');
            return;
        }

        row.remove();
        reindexIngredients();
        updateRemoveButtons();
    });

    function reindexIngredients() {
        const rows = ingredientsList.querySelectorAll('.ingredient-row');
        rows.forEach((row, index) => {
            row.dataset.index = index;
            row.querySelectorAll('input').forEach(input => {
                input.name = input.name.replace(/\[\d+\]/, `[${index}]`);
            });
        });
        ingredientIndex = rows.length;
    }

    function updateRemoveButtons() {
        const rows = ingredientsList.querySelectorAll('.ingredient-row');
        rows.forEach((row) => {
            const btn = row.querySelector('.remove-ingredient-btn');
            if (btn) {
                btn.style.visibility = rows.length <= 1 ? 'hidden' : 'visible';
            }
        });
    }

    updateRemoveButtons();
}

// =============================================
// ➕ SCHRITTE HINZUFÜGEN/ENTFERNEN
// =============================================
function addStep() {
    const stepsList = document.getElementById('steps-list');
    const addStepBtn = document.getElementById('add-step-btn');

    if (!stepsList || !addStepBtn) return;

    let stepIndex = stepsList.querySelectorAll('.step-row').length;

    addStepBtn.addEventListener('click', () => {
        const row = document.createElement('div');
        row.className = 'step-row w-full md:flex md:items-start gap-4';
        row.dataset.index = stepIndex;

        row.innerHTML = `
            <div class="w-full md:w-1/12 flex md:justify-center items-center">
                <span class="step-number text-2xl font-bold text-emerald-500">${stepIndex + 1}</span>
            </div>
            <div class="w-full md:w-10/12">
                <textarea name="steps[${stepIndex}][instruction]" rows="3" placeholder="z.B. Mehl in eine große Schüssel sieben..." class="block w-full min-w-0 grow bg-transparent py-1.5 pr-3 pl-1 text-base text-stone-400 placeholder:text-gray-500 sm:text-sm/6 rounded-md outline-1 outline-offset-1 outline-white/10 focus-within:outline-2 focus-within:-outline-offset-2 focus-within:outline-emerald-500/50 resize-y"></textarea>
            </div>
            <div class="w-full md:w-1/12 flex md:justify-center items-start pt-6">
                <button type="button" class="remove-step-btn text-red-400 hover:text-red-300 transition-colors cursor-pointer">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                </button>
            </div>
        `;

        stepsList.appendChild(row);
        stepIndex++;
        updateStepRemoveButtons();
    });

    stepsList.addEventListener('click', (e) => {
        const removeBtn = e.target.closest('.remove-step-btn');
        if (!removeBtn) return;

        const row = removeBtn.closest('.step-row');
        if (!row) return;

        if (stepsList.querySelectorAll('.step-row').length <= 1) {
            row.querySelector('textarea').value = '';
            return;
        }

        row.remove();
        reindexSteps();
        updateStepRemoveButtons();
        updateStepNumbers();
    });

    function reindexSteps() {
        const rows = stepsList.querySelectorAll('.step-row');
        rows.forEach((row, index) => {
            row.dataset.index = index;
            const textarea = row.querySelector('textarea');
            textarea.name = textarea.name.replace(/\[\d+\]/, `[${index}]`);
        });
        stepIndex = rows.length;
    }

    function updateStepNumbers() {
        const rows = stepsList.querySelectorAll('.step-row');
        rows.forEach((row, index) => {
            const numberSpan = row.querySelector('.step-number');
            if (numberSpan) numberSpan.textContent = index + 1;
        });
    }

    function updateStepRemoveButtons() {
        const rows = stepsList.querySelectorAll('.step-row');
        rows.forEach((row) => {
            const btn = row.querySelector('.remove-step-btn');
            if (btn) {
                btn.style.visibility = rows.length <= 1 ? 'hidden' : 'visible';
            }
        });
    }

    updateStepRemoveButtons();
}

// =============================================
// 🖼️ BILD-VORSCHAU beim Upload
// =============================================
function imagePreview() {
    const fileInput = document.getElementById('dropzone-file');
    if (!fileInput) return;

    // Container für Vorschau anlegen (falls noch nicht vorhanden)
    let previewContainer = document.getElementById('image-preview-container');
    if (!previewContainer) {
        previewContainer = document.createElement('div');
        previewContainer.id = 'image-preview-container';
        previewContainer.className = 'grid grid-cols-2 md:grid-cols-4 gap-3 mt-3';
        fileInput.closest('label').parentElement.appendChild(previewContainer);
    }

    fileInput.addEventListener('change', (e) => {
        previewContainer.innerHTML = ''; // Alte Vorschauen löschen

        Array.from(e.target.files).forEach((file) => {
            if (!file.type.startsWith('image/')) return;

            const reader = new FileReader();
            reader.onload = (event) => {
                const wrapper = document.createElement('div');
                wrapper.className = 'relative group';
                wrapper.innerHTML = `
                    <img src="${event.target.result}" alt="Vorschau" class="w-full h-32 object-cover rounded-lg border border-stone-700">
                    <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center rounded-lg">
                        <span class="text-white text-xs px-2 py-1 bg-stone-900/80 rounded">Neu</span>
                    </div>
                `;
                previewContainer.appendChild(wrapper);
            };
            reader.readAsDataURL(file);
        });
    });
}

// =============================================
// 👤 USER-MENÜ DROPDOWN (Click-Toggle)
// =============================================
function userMenuToggle() {
    const button = document.getElementById('user-menu-button');
    const dropdown = document.getElementById('user-menu-dropdown');
    const arrow = document.getElementById('user-menu-arrow');

    if (!button || !dropdown) return;

    let isOpen = false;

    function toggleMenu() {
        isOpen = !isOpen;

        if (isOpen) {
            dropdown.classList.remove('opacity-0', 'invisible');
            dropdown.classList.add('opacity-100', 'visible');
            dropdown.style.transform = 'translateY(0)';
            arrow.style.transform = 'rotate(180deg)';
        } else {
            dropdown.classList.add('opacity-0', 'invisible');
            dropdown.classList.remove('opacity-100', 'visible');
            dropdown.style.transform = 'translateY(-10px)';
            arrow.style.transform = 'rotate(0deg)';
        }
    }

    function closeMenu() {
        if (isOpen) {
            isOpen = false;
            dropdown.classList.add('opacity-0', 'invisible');
            dropdown.classList.remove('opacity-100', 'visible');
            dropdown.style.transform = 'translateY(-10px)';
            arrow.style.transform = 'rotate(0deg)';
        }
    }

    // Button-Click
    button.addEventListener('click', (e) => {
        e.stopPropagation();
        toggleMenu();
    });

    // Schließen bei Klick außerhalb
    document.addEventListener('click', (e) => {
        if (!dropdown.contains(e.target) && !button.contains(e.target)) {
            closeMenu();
        }
    });

    // Schließen bei Escape-Taste
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            closeMenu();
        }
    });
}

// Im DOMContentLoaded-Block aufrufen:
document.addEventListener('DOMContentLoaded', () => {
    // ... bestehende Funktionen ...

    // User-Menü
    if (document.getElementById('user-menu-button')) {
        userMenuToggle();
    }
});

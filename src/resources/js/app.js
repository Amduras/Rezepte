import './bootstrap';

document.addEventListener('DOMContentLoaded', function() {
    // Prüfe ob wir auf der Recipe-Seite sind (via DOM-Element)
    if (document.getElementById('carousel-track') ||
        document.getElementById('ingredients-list')) {
        recipe();
    }

    if(document.getElementById('ingredients-list') ||
        document.getElementById('add-ingredient-btn')) {
        addIngredient();
    }

    if (document.getElementById('steps-list') ||
        document.getElementById('add-step-btn')) {
        addStep();
    }
});

function recipe(){
    const track = document.getElementById('carousel-track');
    const prevBtn = document.getElementById('carousel-prev');
    const nextBtn = document.getElementById('carousel-next');
    const dots = document.querySelectorAll('.carousel-dot');
    const servLeft = document.getElementById('servings-left');
    const servRight = document.getElementById('servings-right');

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
            track.classList.add('is-resetting');
            track.style.transition = 'none';

            currentIndex = targetIndex;

            updateVisuals();
            updateDots();

            const containerWidth = track.parentElement.offsetWidth;
            const itemWidth = allItems[0].offsetWidth;
            const offset = (containerWidth / 2) - (itemWidth / 2) - (currentIndex * itemWidth);

            track.style.transform = `translate3d(${offset}px, 0, 0)`;

            void track.offsetWidth;

            requestAnimationFrame(() => {
                track.classList.remove('is-resetting');

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
    })

    mobile_ingredients();
}

function mobile_ingredients() {
    const sectionRight = document.getElementById('section-right');
    const showBtn = document.getElementById('ingredients__show');
    const closeBtn = document.getElementById('ingredients__close');

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
        if (window.innerWidth < 768) {
            openMobileMenu();
        }
    });

    closeBtn.addEventListener('click', () => {
        if (window.innerWidth < 768) {
            closeMobileMenu();
        }
    });

    window.addEventListener('resize', () => {
        if (window.innerWidth >= 768) {
            sectionRight.classList.remove('fixed', 'inset-0', 'z-50', 'overflow-y-auto');
            closeBtn.classList.add('hidden');
            showBtn.classList.remove('hidden');
        }
    });
}

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
    if (!Array.isArray(ingredientTexts)) {
        console.error("Es wurde kein Array übergeben!");
        return [];
    }

    const factor = newPortions / originalPortions;

    return ingredientTexts.map(item => {
        const parsed = parseIngredient(item);

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

function addIngredient(){
    const ingredientsList = document.getElementById('ingredients-list');
    const addIngredientBtn = document.getElementById('add-ingredient-btn');

    if (!ingredientsList || !addIngredientBtn) return;

    let ingredientIndex = 1;

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

    // --- Zutat entfernen (Event Delegation) ---
    ingredientsList.addEventListener('click', (e) => {
        const removeBtn = e.target.closest('.remove-ingredient-btn');
        if (!removeBtn) return;

        const row = removeBtn.closest('.ingredient-row');
        if (!row) return;

        // Mindestens eine Zeile muss bleiben
        if (ingredientsList.querySelectorAll('.ingredient-row').length <= 1) {
            row.querySelectorAll('input').forEach(input => input.value = '');
            return;
        }

        row.remove();
        reindexIngredients();
        updateRemoveButtons();
    });

    // --- Indizes nach dem Löschen neu nummerieren ---
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

    // --- Lösch-Button bei der letzten Zeile ausblenden ---
    function updateRemoveButtons() {
        const rows = ingredientsList.querySelectorAll('.ingredient-row');
        rows.forEach((row) => {
            const btn = row.querySelector('.remove-ingredient-btn');
            if (btn) {
                btn.style.visibility = rows.length <= 1 ? 'hidden' : 'visible';
            }
        });
    }

    // Initial aufrufen
    updateRemoveButtons();
}

// =============================================
// Zubereitungsschritte: Hinzufügen & Entfernen
// =============================================

function addStep() {
    const stepsList = document.getElementById('steps-list');
    const addStepBtn = document.getElementById('add-step-btn');

    // 🛑 Früher Exit
    if (!stepsList || !addStepBtn) return;

    let stepIndex = 1;

    // --- Neuen Schritt hinzufügen ---
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
        updateStepNumbers();
    });

    // --- Schritt entfernen (Event Delegation) ---
    stepsList.addEventListener('click', (e) => {
        const removeBtn = e.target.closest('.remove-step-btn');
        if (!removeBtn) return;

        const row = removeBtn.closest('.step-row');
        if (!row) return;

        // Mindestens eine Zeile muss bleiben
        if (stepsList.querySelectorAll('.step-row').length <= 1) {
            row.querySelector('textarea').value = '';
            return;
        }

        row.remove();
        reindexSteps();
        updateStepRemoveButtons();
        updateStepNumbers();
    });

    // --- Indizes nach dem Löschen neu nummerieren ---
    function reindexSteps() {
        const rows = stepsList.querySelectorAll('.step-row');
        rows.forEach((row, index) => {

            row.dataset.index = index;
            const textarea = row.querySelector('textarea');
            textarea.name = textarea.name.replace(/\[\d+\]/, `[${index}]`);
        });
        stepIndex = rows.length;
    }

    // --- Schritt-Nummern aktualisieren ---
    function updateStepNumbers() {
        const rows = stepsList.querySelectorAll('.step-row');
        rows.forEach((row, index) => {
            const numberSpan = row.querySelector('.step-number');
            if (numberSpan) {
                numberSpan.textContent = index + 1;
            }
        });
    }

    // --- Lösch-Button bei der letzten Zeile ausblenden ---
    function updateStepRemoveButtons() {
        const rows = stepsList.querySelectorAll('.step-row');
        rows.forEach((row) => {
            const btn = row.querySelector('.remove-step-btn');
            if (btn) {
                btn.style.visibility = rows.length <= 1 ? 'hidden' : 'visible';
            }
        });
    }

    // Initial aufrufen
    updateStepRemoveButtons();
}

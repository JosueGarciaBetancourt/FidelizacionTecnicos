document.addEventListener("DOMContentLoaded", function () {
    // Asegurar que externFunctions exista sin sobrescribirlo
    window.externFunctions = window.externFunctions || {};

    // Función para registrar funciones específicas por dropdown
    window.registerExternFunction = function (dropdownId, name, fn) {
        if (!window.externFunctions[dropdownId]) {
            window.externFunctions[dropdownId] = {}; // Crear espacio para este dropdown
        }

        if (typeof fn === "function") {
            window.externFunctions[dropdownId][name] = fn;
        } else {
            console.warn(`La función "${name}" no es válida para el dropdown "${dropdownId}".`);
        }
    };

    // Función para obtener funciones específicas de un dropdown
    window.getExternFunction = function (dropdownId, name) {
        return window.externFunctions[dropdownId]?.[name] || null;
    };

    const dropdownContainers = document.querySelectorAll(".multiSelectDropdownContainer");

    dropdownContainers.forEach((dropdownContainer) => {
        const dropdownId = dropdownContainer.id; 
        const customSelect = dropdownContainer.querySelector(".custom-select");
        const selectBox = customSelect.querySelector(".select-box");
        const optionsContainer = customSelect.querySelector(".optionsMultiSelectDropdown");
        const selectedOptionsContainer = customSelect.querySelector(".selected-options");
        const tagsInput = customSelect.querySelector(".tags_input");
        const options = Array.from(customSelect.querySelectorAll(".option:not(.all-tags)"));
        const allTagsOption = customSelect.querySelector(".option.all-tags");
        const searchInput = customSelect.querySelector(".search-tags");
        const clearButton = customSelect.querySelector(".clear");
        const noResultMessage = customSelect.querySelector(".no-result-message");
        const emptyDataMessage = customSelect.querySelector(".empty-data-message");
        const arrow = customSelect.querySelector(".arrow");
        const arrowIcon = arrow.querySelector("i");

        function checkEmptyOptions() {
            if (options.length === 0) {
                emptyDataMessage.style.display = "block";
                if (noResultMessage) {
                    noResultMessage.style.display = "none";
                }
            } else if (emptyDataMessage) {
                emptyDataMessage.style.display = "none";
            }
        }

        function updateSelectedOptions() {
            const activeOptions = options.filter(option => option.classList.contains("active"));
            const selectedValues = activeOptions.map(option => option.getAttribute("data-value"));
            const selectedTexts = activeOptions.map(option => option.textContent.trim());

            tagsInput.value = selectedValues.join(', '); // Rellenar hidden input

            if (selectedTexts.length === 0) {
                selectedOptionsContainer.innerHTML = '<span class="selectedOptions__placeholder">Seleccionar oficio</span>';
            } else {
                let tagsHTML = "";
                selectedTexts.forEach((text, index) => {
                    const value = selectedValues[index];

                    tagsHTML += `
                        <span class="tag">
                            ${text}
                            <span class="remove-tag" data-value="${value}">&times;</span>
                        </span>
                    `;

                    // Obtener funciones dinámicamente en cada iteración
                    const selectOptionOficio = window.getExternFunction(dropdownId, "selectOptionOficio");

                    if (selectOptionOficio) {
                        selectOptionOficio(value.trim());
                    }
                });
                selectedOptionsContainer.innerHTML = tagsHTML;
            }

            selectedOptionsContainer.querySelectorAll(".remove-tag").forEach(removeTag => {
                removeTag.addEventListener("click", function () {
                    const valueToRemove = this.getAttribute("data-value");
                    const optionToRemove = customSelect.querySelector(`.option[data-value="${valueToRemove}"]`);

                    if (optionToRemove) {
                        optionToRemove.classList.remove("active");
                    }

                    // Obtener función dinámica en cada evento
                    const deleteOptionOficio = window.getExternFunction(dropdownId, "deleteOptionOficio");
                    if (deleteOptionOficio) {
                        deleteOptionOficio(valueToRemove);
                    }

                    updateSelectedOptions();
                });
            });
        }

        options.forEach(option => {
            option.addEventListener("click", function () {
                this.classList.toggle("active");

                const deleteOptionOficio = window.getExternFunction(dropdownId, "deleteOptionOficio");
                
                if (!this.classList.contains("active") && deleteOptionOficio) {
                    deleteOptionOficio(this.textContent);
                }

                updateSelectedOptions();
            });
        });

        if (allTagsOption) {
            allTagsOption.addEventListener("click", function () {
                const isCurrentlyActive = options.every(option => option.classList.contains("active"));
                options.forEach(option => {
                    const selectOptionOficio = window.getExternFunction(dropdownId, "selectOptionOficio");
                    const deleteOptionOficio = window.getExternFunction(dropdownId, "deleteOptionOficio");

                    if (!isCurrentlyActive && selectOptionOficio) {
                        selectOptionOficio(option.textContent.trim());
                    } else if (deleteOptionOficio) {
                        deleteOptionOficio(option.textContent.trim());
                    }

                    option.classList.toggle("active", !isCurrentlyActive);
                });
                updateSelectedOptions();
            });
        }

        function clearTagsMultiSelectDropdown() {
            const activeOptions = options.filter(option => option.classList.contains("active"));
            
            activeOptions.forEach(activeOption => {
                activeOption.classList.remove("active");
            });

            updateSelectedOptions();
        }

        window.registerExternFunction(dropdownId, "clearTagsMultiSelectDropdown", clearTagsMultiSelectDropdown);

        function fillTagsMultiSelectDropdown(optionsArray) {
            const foundOptions = options.filter(option => 
                optionsArray.some(value => option.textContent.includes(value))
            );
        
            // Marcamos como "active" las opciones encontradas
            foundOptions.forEach(foundOption => {
                foundOption.classList.add("active");
            });
        
            updateSelectedOptions();
        }

        window.registerExternFunction(dropdownId, "fillTagsMultiSelectDropdown", fillTagsMultiSelectDropdown);

        selectBox.addEventListener("click", function (event) {
            if (!event.target.closest(".tag")) {
                optionsContainer.classList.toggle("opened");
                selectBox.classList.toggle("activeFocus");
                arrowIcon.classList.toggle("fa-angle-down");
                arrowIcon.classList.toggle("fa-angle-up");
            }
        });

        document.addEventListener("click", function (event) {
            if (!dropdownContainer.contains(event.target) && !event.target.classList.contains("remove-tag")) {
                optionsContainer.classList.remove("opened");
                selectBox.classList.remove("activeFocus");
                arrowIcon.classList.remove("fa-angle-up");
                arrowIcon.classList.add("fa-angle-down");
            }
        });

        searchInput?.addEventListener("input", function () {
            const searchTerm = this.value.toLowerCase().trim();
            let visibleOptionsCount = 0;

            options.forEach(option => {
                const isMatch = option.textContent.toLowerCase().includes(searchTerm);
                option.style.display = isMatch ? "block" : "none";
                if (isMatch) visibleOptionsCount++;
            });

            noResultMessage.style.display = visibleOptionsCount === 0 ? "block" : "none";
        });

        clearButton?.addEventListener("click", function () {
            searchInput.value = "";
            options.forEach(option => option.style.display = "block");
            noResultMessage.style.display = "none";
        });

        checkEmptyOptions();
    });
});

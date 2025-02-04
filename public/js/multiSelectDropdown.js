document.addEventListener("DOMContentLoaded", function() {
    const dropdownContainer = document.querySelector(".multiSelectDropdownContainer");
    const customSelect = document.querySelector(".custom-select");
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

    function checkEmptyOptions() {
        if (options.length === 0) {
            emptyDataMessage.style.display = "block";
            noResultMessage.style.display = "none";
        } else {
            emptyDataMessage.style.display = "none";
        }
    }

    function updateSelectedOptions() {
        const activeOptions = options.filter(option => option.classList.contains("active"));
        const selectedValues = activeOptions.map(option => option.getAttribute("data-value"));
        const selectedTexts = activeOptions.map(option => option.textContent.trim());

        tagsInput.value = selectedValues.join(', ');

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
            });
            selectedOptionsContainer.innerHTML = tagsHTML;
        }

        selectedOptionsContainer.querySelectorAll(".remove-tag").forEach(removeTag => {
            removeTag.addEventListener("click", function() {
                const valueToRemove = this.getAttribute("data-value");
                const optionToRemove = customSelect.querySelector(`.option[data-value="${valueToRemove}"]`);
                if (optionToRemove) {
                    optionToRemove.classList.remove("active");
                }
                updateSelectedOptions();
            });
        });
    }

    options.forEach(option => {
        option.addEventListener("click", function() {
            this.classList.toggle("active");
            updateSelectedOptions();
        });
    });
    
    if (allTagsOption) {
        allTagsOption.addEventListener("click", function() {
            const isCurrentlyActive = options.every(option => option.classList.contains("active"));
            options.forEach(option => {
                option.classList.toggle("active", !isCurrentlyActive);
            });
            updateSelectedOptions();
        });
    }

    selectBox.addEventListener("click", function(event) {
        if (!event.target.closest(".tag")) {
            optionsContainer.classList.toggle("open");
        }
    });

    document.addEventListener("click", function(event) {
        if (!dropdownContainer.contains(event.target) && !event.target.classList.contains("remove-tag")) {
            optionsContainer.classList.remove("open");
        }
    });

    searchInput.addEventListener("input", function() {
        const searchTerm = this.value.toLowerCase().trim();
        let visibleOptionsCount = 0;

        options.forEach(option => {
            const isMatch = option.textContent.toLowerCase().includes(searchTerm);
            option.style.display = isMatch ? "block" : "none";
            if (isMatch) visibleOptionsCount++;
        });

        noResultMessage.style.display = visibleOptionsCount === 0 ? "block" : "none";
    });

    clearButton.addEventListener("click", function() {
        searchInput.value = "";
        options.forEach(option => option.style.display = "block");
        noResultMessage.style.display = "none";
    });

    checkEmptyOptions();
});

let attributesArray = attributesData;

class wineSearch {
	constructor() {
		this.attributes = attributesArray;
		this.inputs;
		this.labels;
		this.checkboxes;
	}

	getInputs() {
		this.inputs = document.querySelectorAll('.js--attribute-input');
		console.log(this.inputs);
	}
	getLabels() {
		this.labels = document.querySelectorAll('.js--attribute-terms');
		console.log(this.labels);
	}
	getCheckboxes() {
		this.checkboxes = document.querySelectorAll('.js--form-checkbox');
		console.log(this.checkboxes);
	}

	toggleLabel(checkAttribute, checkTerm, label) {
		if (checkAttribute && checkTerm) {
			label.style.display = 'flex';
		} else if (checkAttribute && !checkTerm) {
			label.style.display = 'none';
		}
	}

	highlightLabel(checkbox) {
		if (checkbox.checked) {
			checkbox.parentElement.style.backgroundColor = '#2F7AE5';
			checkbox.parentElement.style.color = 'white';
		} else {
			checkbox.parentElement.style.backgroundColor = '#EEEEEE';
			checkbox.parentElement.style.color = 'black';
		}
	}

	attributeSearch() {
		this.inputs.forEach((e) => {
			e.addEventListener('input', () => {
				this.labels.forEach((label) => {
					this.toggleLabel(label.dataset.attribute.toLowerCase() == e.dataset.attribute.toLowerCase(), label.dataset.term.toLowerCase().includes(e.value.toLowerCase()), label);
				})
			});
		})
	}

	checkboxListener() {
		this.checkboxes.forEach((e) => {
			e.addEventListener('change', () => {
				this.highlightLabel(e);
			});
		});
	}
}

let attributesArray = attributesData;

class wineSearch {
	constructor() {
		this.attributes = attributesArray;
		this.inputs;
		this.labels;
	}

	getInputs() {
		this.inputs = document.querySelectorAll('.js--attribute-input');
		console.log(this.inputs);
	}
	getLabels() {
		this.labels = document.querySelectorAll('.js--attribute-terms');
		console.log(this.labels);
	}

	attributeSearch() {

		this.inputs.forEach((e) => {
			e.addEventListener('input', () => {
				// console.log(attributes[e.dataset.attribute]);
				// console.log(e.value, e.dataset.attribute);
				// this.attributes[e.dataset.attribute].forEach((term) => {
				// 	if (term.toLowerCase().includes(e.value.toLowerCase())) {
				this.labels.forEach((label) => {
					if (label.dataset.attribute.toLowerCase() == e.dataset.attribute.toLowerCase() && label.dataset.term.toLowerCase().includes(e.value.toLowerCase())) {
						console.log(e.value, label);
						label.style.display = 'flex';
						// label.dataset.attribute.toLowerCase() == term.toLowerCase() ? console.log(label) : console.log('error');
					} else if (label.dataset.attribute.toLowerCase() == e.dataset.attribute.toLowerCase() && !label.dataset.term.toLowerCase().includes(e.value.toLowerCase())) {
						label.style.display = 'none';

					}
				})
				// 	}
				// });
			});
		})
	}
}

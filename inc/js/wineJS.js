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
		let attributes = this.attributes;

		this.inputs.forEach((e) => {
			e.addEventListener('input', () => {
				// console.log(attributes[e.dataset.attribute]);
				// console.log(e.value, e.dataset.attribute);
				attributes[e.dataset.attribute].forEach((attribute) => {
					console.log(attribute);
					attribute.toLowerCase().includes(e.value.toLowerCase()) ? this.labels.forEach((term) => {
						term.dataset.term = attribute ? console.log(term) : console.log('error');
					}) : console.log(false);
				});
			});
		})
	}
}

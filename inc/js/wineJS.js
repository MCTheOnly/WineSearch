let attributesArray = attributesData;

class wineSearch {
	constructor() {
		this.attributes = attributesArray;
		// this.attributeInputs = document.querySelectorAll('.js--attribute-input');
	}
	getInputs() {
		return document.querySelectorAll('.js--attribute-input');
	}
	attributeSearch() {
		let inputs = this.getInputs();
		let attributes = this.attributes;

		inputs.forEach((e) => {
			e.addEventListener('input', () => {
				console.log(attributes[e.dataset.attribute]);
				console.log(e.value, e.dataset.attribute);
				attributes[e.dataset.attribute].forEach((attribute) => {
					console.log(attribute);
					attribute.includes(e.value) ? console.log(attribute.search(e.value)) : console.log(false);

				});

			});
		})
	}
}

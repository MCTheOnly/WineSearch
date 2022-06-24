'use strict';

// ------------------------------------------------------- VARIABLES //

let $tableBody;
let $timeHeader;
let $durationsCell;
let $tableRows;
let $nodeTable;
let $headerDurationIndex;
let $currentIsAscending;

// ------------------------------------------------------- INITIALIZE //

const initialize = () => {
	prepareDOMelements();
	functions();
};

// ------------------------------------------------------- PREPARE DOM ELEMENTS //

const prepareDOMelements = () => {
	$tableBody = document.querySelector('.table__tbody');
	$timeHeader = document.querySelector('.time-header');
	$durationsCell = document.querySelectorAll('.duration');
	$tableRows = Array.from($tableBody.querySelectorAll("tr"));
	$nodeTable = $timeHeader.parentElement.parentElement.parentElement;
	$headerDurationIndex = Array.prototype.indexOf.call($timeHeader.parentElement.children, $timeHeader);
	$currentIsAscending = $timeHeader.classList.contains("th-sort-asc");
};

// ------------------------------------------------------- FUNCTIONS //

const functions = () => {
	const changeDurationFormat = (duration) => {
		let hours = Math.floor(duration / 3600);
		let minutes = Math.floor((duration % 3600) / 60);
		let seconds = Math.floor(duration % 60);
		let rightFormatTime = "";

		if (hours > 0) {
			rightFormatTime += hours + ":" + (minutes < 10 ? "0" : "");
		};

		rightFormatTime += minutes + ":" + (seconds < 10 ? "0" : "");
		rightFormatTime += seconds;
		return rightFormatTime;
	};

	$durationsCell.forEach(durationCell => {
		durationCell.innerHTML = changeDurationFormat(durationCell.innerHTML);
	});

	const sortTableByDurationColumn = (table, durationColumn, asc = true) => {
		const directionModifier = asc ? 1 : -1;
		let durationCol = durationColumn + 1;

		const sortedRows = $tableRows.sort((a, b) => {
			const durationRowsA = a.querySelector(`td:nth-child(${durationCol})`).textContent.trim();
			const durationRowsB = b.querySelector(`td:nth-child(${durationCol})`).textContent.trim();
			return durationRowsA > durationRowsB ? (directionModifier) : (-1 * directionModifier);
		});

		$tableBody.append(...sortedRows);
		table.querySelector(`th:nth-child(${durationCol})`).classList.toggle("asc-sort", asc);
		table.querySelector(`th:nth-child(${durationCol})`).classList.toggle("desc-sort", !asc);
	};

	$timeHeader.addEventListener("click", () => {
		let currentIsAscending = $timeHeader.classList.contains("asc-sort");
		sortTableByDurationColumn($nodeTable, $headerDurationIndex, !currentIsAscending);
	});
};

// ------------------------------------------------------- DOM CONTENT LOADED //

document.addEventListener('DOMContentLoaded', initialize);

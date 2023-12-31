import { bubble, listen } from 'svelte/internal';

/**
 * @description Dispatch event on click outside of node
 * @example <div use:clickOutside on:click_outside={handleClickOutside}>
 *
 * @param {HTMLElement} node
 *
 * @returns {object} destroy
 */
export function clickOutside(node: HTMLElement) {
	const handleClick = (event: MouseEvent) => {
		if (node && !node.contains(event.target as HTMLElement) && !event.defaultPrevented) {
			node.dispatchEvent(new CustomEvent('click_outside', { detail: { node } }));
		}
	};
	document.addEventListener('click', handleClick, true);
	return {
		destroy() {
			document.removeEventListener('click', handleClick, true);
		}
	};
}

import type { ActionReturn } from 'svelte/action';

interface Attributes {
	'on:clickoutside'?: (e: CustomEvent<void>) => void;
}

type Callback = () => unknown;

export function clickOutsideAction(
	node: HTMLElement,
	callback?: Callback
): ActionReturn<Record<string, never>, Attributes> {
	const handleClick = (event: Event) => {
		if (event.target !== null && !node.contains(event.target as Node)) {
			node.dispatchEvent(new CustomEvent('clickoutside'));
			callback?.();
		}
	};

	const stop = listen(document, 'click', handleClick, true);

	return {
		destroy() {
			stop();
		}
	};
}
/**
 * @description format datetime to dd.mm.yyyy hh:mm
 *
 * @param {string} datetime
 *
 * @returns {string} dd.mm.yyyy hh:mm
 *
 */
export function formatDatetime(datetime: string, locale = 'en-US') {
	console.log('datetime', datetime);
	const d = new Date(datetime).toLocaleDateString(locale, {
		year: 'numeric',
		month: 'long',
		day: 'numeric'
	});
	console.log('d', d);
	// const year = d.getFullYear();
	// const month = ('0' + (d.getMonth() + 1)).slice(-2);
	// const day = ('0' + d.getDate()).slice(-2);
	// const hour = ('0' + d.getHours()).slice(-2);
	// const minute = ('0' + d.getMinutes()).slice(-2);
	// return `${day}.${month}.${year} ${hour}:${minute}`;
}

/**
 *
 * @description format date to dd.mm.yyyy
 * @param {string} date
 * @param {string} locale
 *
 * @returns {string} dd.mm.yyyy
 */
export function formatDate(date: string, locale = 'en-US') {
	return new Date(date).toLocaleDateString(locale, {
		year: 'numeric',
		month: 'long',
		day: 'numeric'
	});
}

export const capitalize = (str: string) => str.charAt(0).toUpperCase() + str.slice(1);

export function setParamToUrl(param: string, value: string) {
	const urlParams = new URLSearchParams(window.location.search);
	// console.log('urlParams', value);
	if (value === '' || value === null || value === undefined || !value) {
		// console.log('delete', param);
		urlParams.delete(param);
		const newUrl = '?' + urlParams.toString();
		history.pushState({ path: newUrl }, '', newUrl);
	} else {
		urlParams.set(param, value);
		const newUrl = '?' + urlParams.toString();
		history.pushState({ path: newUrl }, '', newUrl);
		window.location.hash = '';
		// remove hash from url
		// const newUrl1 = window.location.href.split('#')[0];
		// window.history.replaceState(null, '', newUrl1);
	}
}

export function getParamFromUrl(param: string) {
	const urlParams = new URLSearchParams(window.location.search);
	return urlParams.get(param);
}

export function delay(ms: number = 300) {
	return new Promise((resolve) => setTimeout(resolve, ms));
}

export function getEventsAction(component) {
	return (node) => {
		const events = Object.keys(component.$$.callbacks);
		const listeners = [];
		events.forEach((event) => listeners.push(listen(node, event, (e) => bubble(component, e))));
		return {
			destroy: () => {
				listeners.forEach((listener) => listener());
			}
		};
	};
}

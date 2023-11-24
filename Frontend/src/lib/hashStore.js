import { derived, writable } from 'svelte/store';

export function createUrlStore(ssrUrl) {
	if (typeof window === 'undefined') {
		const { subscribe } = writable({ url: new URL(ssrUrl), hash: '' });
		return { subscribe };
	}

	const href = writable(window.location.href);

	const originalPushState = history.pushState;
	const originalReplaceState = history.replaceState;

	const updateHref = () => href.set(window.location.href);

	history.pushState = function () {
		originalPushState.apply(this, arguments);
		updateHref();
	};

	history.replaceState = function () {
		originalReplaceState.apply(this, arguments);
		updateHref();
	};

	window.addEventListener('popstate', updateHref);
	window.addEventListener('hashchange', updateHref);

	const setHash = (newHash) => {
		const currentUrl = new URL(window.location.href);
		currentUrl.hash = newHash;
		history.pushState({}, '', currentUrl.toString());
	};

	const getHash = () => {
		const currentUrl = new URL(window.location.href);
		return currentUrl.hash;
	};

	return {
		subscribe: derived(href, ($href) => new URL($href)).subscribe,
		setHash,
		getHash
	};
}

export default createUrlStore();

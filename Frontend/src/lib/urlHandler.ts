export default class URLHandler {
	private url: URL;

	constructor() {
		this.url = new URL(window.location.href);
	}

	add(name: string, value: string): void {
		this.url.searchParams.set(name, value);
		this.updateUrl();
	}

	read(name: string): string | null {
		return this.url.searchParams.get(name);
	}

	remove(name: string): void {
		this.url.searchParams.delete(name);
		this.updateUrl();
	}

	private updateUrl(): void {
		window.history.pushState({}, '', this.url.href);
	}
}

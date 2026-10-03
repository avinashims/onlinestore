(function () {
	'use strict';

	const data = window.booknestData || {};

	function setCookie(name, value, days) {
		const d = new Date();
		d.setTime(d.getTime() + days * 24 * 60 * 60 * 1000);
		document.cookie = name + '=' + value + ';expires=' + d.toUTCString() + ';path=/;SameSite=Lax';
	}

	/* Dark mode */
	const themeToggle = document.querySelector('[data-theme-toggle]');
	if (themeToggle) {
		themeToggle.addEventListener('click', function () {
			const isDark = document.body.classList.toggle('booknest-dark');
			setCookie('booknest_theme', isDark ? 'dark' : 'light', 365);
			themeToggle.setAttribute('aria-pressed', isDark ? 'true' : 'false');
		});
	}

	/* Mobile nav */
	const navToggle = document.querySelector('[data-nav-toggle]');
	const header = document.getElementById('site-header');
	if (navToggle && header) {
		navToggle.addEventListener('click', function () {
			const open = header.classList.toggle('nav-open');
			navToggle.setAttribute('aria-expanded', open ? 'true' : 'false');
		});
		document.addEventListener('keydown', function (e) {
			if (e.key === 'Escape') {
				header.classList.remove('nav-open');
				navToggle.setAttribute('aria-expanded', 'false');
			}
		});
	}

	/* Live search */
	const searchInput = document.querySelector('[data-live-search]');
	const resultsBox = document.getElementById('live-search-results');
	let searchTimer;

	if (searchInput && resultsBox) {
		searchInput.addEventListener('input', function () {
			clearTimeout(searchTimer);
			const term = searchInput.value.trim();
			if (term.length < 2) {
				resultsBox.hidden = true;
				resultsBox.innerHTML = '';
				return;
			}
			searchTimer = setTimeout(function () {
				const url = new URL(data.ajaxUrl);
				url.searchParams.set('action', 'booknest_live_search');
				url.searchParams.set('nonce', data.nonce);
				url.searchParams.set('term', term);
				fetch(url.toString(), { credentials: 'same-origin' })
					.then(function (r) { return r.json(); })
					.then(function (res) {
						if (!res.success) return;
						const items = res.data.items || [];
						if (!items.length) {
							resultsBox.innerHTML = '<p class="live-search-empty">' + (data.i18n?.noResults || 'No results') + '</p>';
						} else {
							resultsBox.innerHTML = items.map(function (item) {
								const img = item.image
									? '<img src="' + item.image + '" alt="" width="48" height="64" loading="lazy">'
									: '<span aria-hidden="true">📖</span>';
								return '<a class="live-search-item" href="' + item.url + '">' + img +
									'<div><strong>' + item.title + '</strong>' +
									(item.author ? '<div class="live-search-author">' + item.author + '</div>' : '') +
									'<div class="live-search-price">' + item.price + '</div></div></a>';
							}).join('');
						}
						resultsBox.hidden = false;
						searchInput.setAttribute('aria-expanded', 'true');
					});
			}, 280);
		});

		document.addEventListener('click', function (e) {
			if (!searchInput.contains(e.target) && !resultsBox.contains(e.target)) {
				resultsBox.hidden = true;
				searchInput.setAttribute('aria-expanded', 'false');
			}
		});
	}

	/* Testimonials */
	const slider = document.querySelector('[data-testimonial-slider]');
	if (slider) {
		const slides = slider.querySelectorAll('[data-testimonial]');
		const dots = slider.querySelectorAll('[data-testimonial-dot]');
		let index = 0;

		function show(i) {
			slides.forEach(function (s, idx) {
				s.hidden = idx !== i;
			});
			dots.forEach(function (d, idx) {
				d.classList.toggle('is-active', idx === i);
			});
			index = i;
		}

		dots.forEach(function (dot, i) {
			dot.addEventListener('click', function () { show(i); });
		});

		if (slides.length) {
			show(0);
			setInterval(function () {
				show((index + 1) % slides.length);
			}, 6000);
		}
	}

	/* Countdown */
	const countdownEl = document.querySelector('[data-countdown]');
	if (countdownEl) {
		const end = countdownEl.getAttribute('data-end');
		const endTime = end ? new Date(end).getTime() : Date.now() + 7 * 24 * 60 * 60 * 1000;

		function tick() {
			const diff = Math.max(0, endTime - Date.now());
			const d = Math.floor(diff / 86400000);
			const h = Math.floor((diff % 86400000) / 3600000);
			const m = Math.floor((diff % 3600000) / 60000);
			const s = Math.floor((diff % 60000) / 1000);
			countdownEl.querySelector('[data-days]').textContent = String(d).padStart(2, '0');
			countdownEl.querySelector('[data-hours]').textContent = String(h).padStart(2, '0');
			countdownEl.querySelector('[data-mins]').textContent = String(m).padStart(2, '0');
			countdownEl.querySelector('[data-secs]').textContent = String(s).padStart(2, '0');
		}
		tick();
		setInterval(tick, 1000);
	}

	/* Product tabs */
	document.querySelectorAll('[data-product-tabs]').forEach(function (tabs) {
		const nav = tabs.querySelectorAll('[data-tab-target]');
		const panels = tabs.querySelectorAll('[data-tab-panel]');
		nav.forEach(function (btn) {
			btn.addEventListener('click', function () {
				const id = btn.getAttribute('data-tab-target');
				nav.forEach(function (b) { b.classList.toggle('is-active', b === btn); });
				panels.forEach(function (p) {
					p.hidden = p.getAttribute('data-tab-panel') !== id;
				});
			});
		});
	});

	/* Sticky ATC mobile */
	const stickyAtc = document.querySelector('[data-sticky-atc]');
	const singleAtc = document.querySelector('.single-product .cart');
	if (stickyAtc && singleAtc && 'IntersectionObserver' in window) {
		const obs = new IntersectionObserver(function (entries) {
			stickyAtc.classList.toggle('is-visible', !entries[0].isIntersecting);
		}, { threshold: 0 });
		obs.observe(singleAtc);
	}
})();

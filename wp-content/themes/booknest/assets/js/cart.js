(function () {
	'use strict';

	const data = window.booknestData || {};
	const drawer = document.querySelector('[data-cart-drawer]');

	function openDrawer() {
		if (!drawer) return;
		drawer.classList.add('is-open');
		drawer.setAttribute('aria-hidden', 'false');
		document.body.style.overflow = 'hidden';
		refreshMiniCart();
	}

	function closeDrawer() {
		if (!drawer) return;
		drawer.classList.remove('is-open');
		drawer.setAttribute('aria-hidden', 'true');
		document.body.style.overflow = '';
	}

	document.querySelectorAll('[data-cart-open]').forEach(function (btn) {
		btn.addEventListener('click', openDrawer);
	});

	if (drawer) {
		drawer.querySelector('[data-cart-close]')?.addEventListener('click', closeDrawer);
		drawer.querySelector('.cart-drawer__overlay')?.addEventListener('click', closeDrawer);
	}

	function refreshMiniCart() {
		const body = drawer?.querySelector('[data-mini-cart-body]');
		if (!body) return;
		const form = new FormData();
		form.append('action', 'booknest_mini_cart');
		form.append('nonce', data.nonce);
		fetch(data.ajaxUrl, { method: 'POST', body: form, credentials: 'same-origin' })
			.then(function (r) { return r.json(); })
			.then(function (res) {
				if (res.success && res.data.html) {
					body.innerHTML = res.data.html;
				}
			});
	}

	if (typeof jQuery !== 'undefined') {
		jQuery(document.body).on('added_to_cart', function () {
			openDrawer();
		});
	}

	/* Quick view */
	const modal = document.querySelector('[data-quick-view-modal]');
	document.querySelectorAll('[data-quick-view]').forEach(function (btn) {
		btn.addEventListener('click', function (e) {
			e.preventDefault();
			const id = btn.getAttribute('data-product-id');
			if (!id || !modal) return;
			const content = modal.querySelector('[data-quick-view-content]');
			content.innerHTML = '<p>Loading…</p>';
			modal.classList.add('is-open');
			const form = new FormData();
			form.append('action', 'booknest_quick_view');
			form.append('nonce', data.nonce);
			form.append('product_id', id);
			fetch(data.ajaxUrl, { method: 'POST', body: form, credentials: 'same-origin' })
				.then(function (r) { return r.json(); })
				.then(function (res) {
					if (res.success) {
						content.innerHTML = res.data.html;
					}
				});
		});
	});

	if (modal) {
		modal.querySelector('[data-modal-close]')?.addEventListener('click', function () {
			modal.classList.remove('is-open');
		});
		modal.addEventListener('click', function (e) {
			if (e.target === modal) modal.classList.remove('is-open');
		});
	}
})();

(function () {
	'use strict';

	const data = window.booknestData || {};
	if (!data.isShop) return;

	const form = document.querySelector('[data-shop-filters]');
	const productsWrap = document.querySelector('[data-shop-products]');

	function runFilter(page) {
		if (!form || !productsWrap) return;
		const fd = new FormData(form);
		fd.append('action', 'booknest_shop_filter');
		fd.append('nonce', data.nonce);
		fd.append('page', page || 1);
		productsWrap.classList.add('is-loading');
		fetch(data.ajaxUrl, { method: 'POST', body: fd, credentials: 'same-origin' })
			.then(function (r) { return r.json(); })
			.then(function (res) {
				productsWrap.classList.remove('is-loading');
				if (res.success) {
					productsWrap.innerHTML = res.data.html;
				}
			});
	}

	if (form) {
		form.addEventListener('change', function () { runFilter(1); });
		const priceRange = form.querySelector('[data-price-range]');
		const priceOut = form.querySelector('[data-price-output]');
		if (priceRange && priceOut) {
			priceRange.addEventListener('input', function () {
				priceOut.textContent = priceRange.value;
			});
		}
	}

	document.querySelectorAll('[data-view-toggle]').forEach(function (btn) {
		btn.addEventListener('click', function () {
			const view = btn.getAttribute('data-view');
			const list = document.querySelector('.woocommerce ul.products');
			if (!list) return;
			document.querySelectorAll('[data-view-toggle]').forEach(function (b) {
				b.classList.toggle('is-active', b === btn);
			});
			list.classList.toggle('list-view', view === 'list');
		});
	});

	const orderSelect = document.querySelector('[data-shop-order]');
	if (orderSelect && form) {
		orderSelect.addEventListener('change', function () {
			let hidden = form.querySelector('input[name="orderby"]');
			if (!hidden) {
				hidden = document.createElement('input');
				hidden.type = 'hidden';
				hidden.name = 'orderby';
				form.appendChild(hidden);
			}
			hidden.value = orderSelect.value;
			runFilter(1);
		});
	}
})();

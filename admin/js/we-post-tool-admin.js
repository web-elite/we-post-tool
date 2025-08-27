(function ($) {
	'use strict';

	jQuery(document).ready(function ($) {

		const modalTexts = {
			custom_link_info: `
        <p>لینک سفارشی مسیری است که پست‌ها در آن نمایش داده می‌شوند.</p>
        <p>مثال: اگر وارد کنید <code class="bg-gray-100 px-1 rounded">my-movie</code> آدرس پست شما می‌شود:</p>
        <p class="mt-1 text-blue-600"><?= $siteUrl ?>/my-movie/SpiderMan-720p.mkv</p>
    `,
			with_front_info: `
        <p>لینک پایه بخشی ثابت از آدرس است که در ساختار پیوند یکتا تعیین می‌شود.</p>
        <p>مثال: اگر ساختار لینک‌ها <code class="bg-gray-100 px-1 rounded">/blog/%postname%/</code> باشد، <code class="bg-gray-100 px-1 rounded">/blog/</code> لینک پایه است.</p>
        <p class="mt-2">- اگر این گزینه فعال نباشد: <code class="bg-gray-100 px-1 rounded"><?= $siteUrl ?>/blog/my-movie/...</code></p>
        <p>- اگر این گزینه فعال باشد: <code class="bg-gray-100 px-1 rounded"><?= $siteUrl ?>/my-movie/...</code></p>
    `
		};

		if (document.getElementById('infoModal')) {
			document.getElementById('infoModal').addEventListener('click', function (e) {
				if (e.target.id === 'infoModal') closeInfoModal();
			});
		}

		if (document.getElementById('custom_link_info')) {
			document.getElementById('custom_link_info').addEventListener('click', function () {
				openInfoModal('custom_link_info');
			});
		}

		if (document.getElementById('with_front_info')) {
			document.getElementById('with_front_info').addEventListener('click', function () {
				openInfoModal('with_front_info');
			});
		}

		if (document.getElementById('closeModal')) {
			document.getElementById('closeModal').addEventListener('click', function () {
				closeInfoModal();
			});
		}

		function openInfoModal(id) {
			document.getElementById('infoModalContent').innerHTML = modalTexts[id];
			document.getElementById('infoModal').classList.remove('hidden');
		}

		function closeInfoModal() {
			document.getElementById('infoModal').classList.add('hidden');
		}

		$('#content-cpt form, #content-tax form').on('submit', function (e) {
			e.preventDefault();
			const $form = $(this);

			$.ajax({
				url: ajaxurl,
				type: 'POST',
				data: $form.serialize(),
				success: function (response) {
					if (response.status == 'success') {
						Swal.fire({
							title: "موفق",
							text: response.message,
							icon: "success",
						});
					} else {
						Swal.fire({
							title: "خطا",
							text: response.message,
							icon: "error",
						});
					}
					$form[0].reset();
				},
				error: function (xhr, status, error) {
					Swal.fire({
						title: "خطا",
						text: xhr.responseJSON.message,
						icon: "error",
					});
				}
			});
		});

		const tabs = document.querySelectorAll('.tab-button');
		const contents = document.querySelectorAll('.tab-content');

		tabs.forEach(tab => {
			tab.addEventListener('click', () => {
				const target = tab.getAttribute('data-tab');

				tabs.forEach(t => {
					t.classList.remove('tab-active');
					t.classList.add('text-gray-500', 'hover:text-gray-700', 'hover:border-gray-300');
				});
				contents.forEach(c => c.classList.add('hidden'));

				tab.classList.add('tab-active');
				tab.classList.remove('text-gray-500', 'hover:text-gray-700', 'hover:border-gray-300');
				document.getElementById('content-' + target).classList.remove('hidden');
			});
		});
	});

})(jQuery);

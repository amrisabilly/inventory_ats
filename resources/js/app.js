import './bootstrap';

import $ from 'jquery';
import DataTable from 'datatables.net-dt';

window.$ = window.jQuery = $;
window.DataTable = DataTable;

window.initDataTable = function (selector, options = {}) {
	return new DataTable(selector, {
		processing: true,
		serverSide: true,
		pageLength: 10,
		lengthMenu: [10, 25, 50, 100],
		language: {
			processing: 'Memproses...',
			lengthMenu: 'Tampilkan _MENU_ data',
			zeroRecords: 'Data tidak ditemukan',
			info: 'Menampilkan _START_ sampai _END_ dari _TOTAL_ data',
			infoEmpty: 'Tidak ada data tersedia',
			infoFiltered: '(disaring dari _MAX_ total data)',
			search: 'Cari:',
			paginate: {
				first: 'Awal',
				last: 'Akhir',
				next: 'Berikutnya',
				previous: 'Sebelumnya',
			},
		},
		...options,
	});
};

$(function () {
	$('.js-repeater').each(function () {
		const $repeater = $(this);
		let index = $repeater.find('.js-repeater-row').length;

		$repeater.on('click', '.js-add-row', function () {
			const template = $repeater.find('.js-repeater-template').html().replaceAll('__INDEX__', index++);
			$repeater.find('.js-repeater-list').append(template);
		});

		$repeater.on('click', '.js-remove-row', function () {
			if ($repeater.find('.js-repeater-row').length > 1) {
				$(this).closest('.js-repeater-row').remove();
			}
		});
	});

	$('.js-reject-trigger').on('click', function () {
		$($(this).data('target')).removeClass('hidden');
	});

	$('.js-modal-close').on('click', function () {
		$($(this).data('target')).addClass('hidden');
	});

	$('.js-stock-physical').on('input', function () {
		const $form = $(this).closest('form');
		const systemStock = Number($form.find('.js-stock-system').val() || 0);
		$form.find('.js-stock-difference').text(Number($(this).val() || 0) - systemStock);
	});

	$('.js-material-select').on('change', function () {
		const stock = $(this).find(':selected').data('stock') || 0;
		const $form = $(this).closest('form');
		$form.find('.js-stock-system').val(stock);
		$form.find('.js-stock-system-label').text(stock);
		$form.find('.js-stock-difference').text(Number($form.find('.js-stock-physical').val() || 0) - Number(stock));
	});
});

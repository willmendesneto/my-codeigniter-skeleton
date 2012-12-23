$(function() {
	function setupTablesorter() {
		var classHeaders = {
			'text': '.sorter-text',
			'digit': '.sorter-digit',
			'currency': '.sorter-currency',
			'ipAddress': '.sorter-ipAddress',
			'url': '.sorter-url',
			'isoDate': '.sorter-isoDate',
			'usLongDate': '.sorter-usLongDate',
			'shortDate': '.sorter-shortDate',
			'time': '.sorter-time',
			'metadata': '.sorter-metadata',
			'digit': '.sorter-digit',
			'money': '.sorter-money',
		};

		var tableHeaders = '', separator;

		$('.tablesorter').each(function (i, e) {
			var self = $(this);
			$.each(classHeaders, function(key, value){
				self.find(key).each(function (pos) {

					if(separator == undefined)
						separator = ',';

					tableHeaders += separator +' '+ $(this).index()+' : { sorter: "'+key+'"}';
				});
			});

			$(this).tablesorter({ widgets: ['zebra'], dateFormat: 'uk', noSorterClass: 'no-sorter', headers: tableHeaders });
		});
	}

	if($('.tablesorter')[0])
		setupTablesorter();
});
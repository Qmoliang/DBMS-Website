(function() {
	var oLanguage = {
		"sProcessing" : "������...",
		"sLengthMenu" : "��ʾ _MENU_ ����",
		"sZeroRecords" : "û��ƥ����",
		"sInfo" : "��ʾ�� _START_ �� _END_ �������� _TOTAL_ ��",
		"sInfoEmpty" : "��ʾ�� 0 �� 0 �������� 0 ��",
		"sInfoFiltered" : "(�� _MAX_ ��������)",
		"sInfoPostFix" : "",
		"sSearch" : "����:",
		"sUrl" : "",
		"sEmptyTable" : "��������Ϊ��",
		"sLoadingRecords" : "������...",
		"sInfoThousands" : ",",
		"oPaginate" : {
			"sFirst" : "��ҳ",
			"sPrevious" : "��ҳ",
			"sNext" : "��ҳ",
			"sLast" : "ĩҳ"
		},
		"oAria" : {
			"sSortAscending" : ": ���������д���",
			"sSortDescending" : ": �Խ������д���"
		}
	};
	$.fn.dataTable.defaults.oLanguage = oLanguage;
	// $.extend($.fn.dataTable.defaults.oLanguage,oLanguage)
})();
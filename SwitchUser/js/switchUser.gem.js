function BitrixGem_SwitchUser_toggleSwitch( elem ){
	if( $('.bitrixgems_switchUser').length > 0 ){
		$('.bitrixgems_switchUser').remove();
	}else{		
		var offset = $(elem).offset();	
		/*Приходится эмулировать вызов диалогового окошка системы, потому что не знаю на каких версиях Битрикса будет работать гем. А должен на любых :)*/	
		$('body').after(
			'<div class="bx-core-window bx-core-dialog bitrixgems_switchUser" style="left: 30% !important; top: '+(offset.top+30)+'px !important ;">\
				<form method="get" action="">\
				<div class="dialog-center"><div class="bitrixgems_switchUser_inner bx-core-dialog-content"><div class="bx-core-dialog-head"><div class="bx-core-dialog-head-content head-block">\
					Переключиться на пользователя с ID: <input size="4" type="text" value="" name="BITRIXGEM_SWITCH_USER_TO" />\
					<hr />\
					Поиск по логину: <input type="text" name="bitrixgems_switchUser_search_user_by_login" class="bitrixgems_switchUser_search"><input type="button" class="bitrixgems_switchUser_search_user_by_login_search" value="Найти!"/>\
					<div class="bitrixgems_switchUser_users"></div>\
				</div></div></div></div>\
				<div class="dialog-head"><div class="l"><div class="r"><div class="c"><span>Переключить пользователя</span></div></div></div></div>\
				<div class="dialog-head-icons"><a class="bx-icon-close" title="Закрыть" onclick="$(\'.bitrixgems_switchUser\').remove();"></a></div>\
				<div class="dialog-foot"><div class="l"><div class="r"><div class="c"><img height="1" border="0" width="90%" style="position: absolute; top: 0pt; left: 0pt;" src="/bitrix/js/main/core/images/line.png"><span>  <input type="submit" value="Переключиться" />  </span></div></div></div></div>\
				</form>\
			</div>'
		);
		$('.bitrixgems_switchUser_search_user_by_login_search').click(function(){
			var login = $('.bitrixgems_switchUser_search').val();
			if( $.trim( login ) == '' ) return;
			$('.bitrixgems_switchUser_users').load( '/bitrix/admin/bitrixgems_simpleresponder.php?gem=SwitchUser&AJAXREQUEST=Y&bitrixgems_switchUser_search_user_by_login='+login );
		});
		$('.bitrixgems_switchUser form').submit(function(){
			if( $('input[name=BITRIXGEM_SWITCH_USER_TO]').val() == '' ) return false;
			return true;
		});
		$('.bitrixgems_switchUser_search_user_by_login').keydown( function(event){
			if ( event.keyCode != 13  ) return true;			
			$('.bitrixgems_switchUser_search_user_by_login_search').click();		 
		});
		
	}
	
}

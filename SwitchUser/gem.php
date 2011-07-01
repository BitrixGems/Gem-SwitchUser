<?php
/**
 * Created by PhpStorm.
 * User: ivariable
 * Date: 01.02.2011
 * Time: 19:03:56
 * To change this template use File | Settings | File Templates.
 */
class BitrixGem_SwitchUser extends BaseBitrixGem{

	protected $aGemInfo = array(
		'GEM'			=> 'SwitchUser',
		'AUTHOR'		=> 'Vladimir Savenkov',
		'AUTHOR_LINK'	=> 'http://bitrixgems.ru/',
		'DATE'			=> '19.02.2011',
		'VERSION'		=> '0.2',
		'NAME' 			=> 'SwitchUser',
		'DESCRIPTION' 	=> "Хелпер для администратора для быстрой смены пользователя. Можно переключиться (авторизоваться) на любой профиль пользователя существующий в системе. Очень пригождается при тестировании сайта под разными группами пользователей.",
		'CHANGELOG'		=> 'Добавлен анализ кнопки "Выйти". Теперь по ее нажатию кнопка смены пользователей исчезает. При обычной же смене пользователя - остается.',
		'REQUIREMENTS'	=> 'jQuery',
		'REQUIRED_MODULES' => array('main'),
	);

	public function initGem(){
		if( defined( 'ADMIN_SECTION' ) ){			
			AddEventHandler(
				'main',
				'OnProlog',
				array( __CLASS__, 'checkUserSwitcher' )
			);

		}
	}
	
	public static function checkUserSwitcher(){
		global $APPLICATION;
		global $USER;

		if( isset( $_GET['logout'] ) ){
			unset( $_SESSION['_BITRIX_GEM_SWITCH_USER_'] );
		}

		if( $USER->IsAdmin() ){
			$_SESSION['_BITRIX_GEM_SWITCH_USER_'] = true;
		}

		if( isset( $_SESSION['_BITRIX_GEM_SWITCH_USER_'] ) ){
			$APPLICATION->AddHeadScript( '/bitrix/js/iv.bitrixgems/SwitchUser/switchUser.gem.js' );
			$APPLICATION->AddHeadString(
				'<style type="text/css">
				.bitrixgems_switchUser { position:absolute !important; }
				.bitrixgems_switchUser .bitrixgems_switchUser_inner { width:500px !important;}
				</style>
				<script type="text/javascript">
				if( typeof jQuery != "undefined" ){
					jQuery(function(){
						jQuery("#bx-panel-admin-toolbar-inner").append(\'<span class="bx-panel-admin-button-separator"></span><a class="bx-panel-admin-button bitrisgems_switchuser_trigger" hidefocus="true" href="#" onclick="BitrixGem_SwitchUser_toggleSwitch(this)"><span class="bx-panel-admin-button-text">Переключить профиль пользователя...</span></a>\');
					})
					}
				</script>
				'
			);
		}

		if( isset( $_SESSION['_BITRIX_GEM_SWITCH_USER_'] ) && isset( $_GET['BITRIXGEM_SWITCH_USER_TO'] ) && ( $_GET['BITRIXGEM_SWITCH_USER_TO'] != $USER->GetID() ) ){
			$USER->Authorize( $_GET['BITRIXGEM_SWITCH_USER_TO'] );
		}
	}
	
	/**
	 * Поиск юзера по логину.
	 * Пришлось делать свой, ане использовать стандартный селект битрикса, потому что у пользователя
	 * под которым сейчас залогинен пользователь может не быть прав на просмотр профилей юзеров.
	 */
	public function processAjaxRequest( $aOptions ){
		$aAnswer = array();
		if( !empty( $aOptions['bitrixgems_switchUser_search_user_by_login'] ) ){
			$oUsers = CUser::GetList(
				($by="login"),
				($order="asc"),
				array(
					'ACTIVE' 	=> 'Y',
					'LOGIN'		=> $aOptions['bitrixgems_switchUser_search_user_by_login'],
				)
			);
			while( $aUser = $oUsers->Fetch() ){
				$aAnswer[] = '<a class="bitrixgems_switchUser_user_id" href="?BITRIXGEM_SWITCH_USER_TO='.$aUser['ID'].'" rel="'.$aUser['ID'].'">['.$aUser['LOGIN'].'] '.$aUser['NAME'].' '.$aUser['LAST_NAME'].' ('.$aUser['ID'].')'.'</a>';
			}
			if( empty( $aAnswer )) $aAnswer = array( '<br />К сожалению пользователя с похожим логином не найдено.' );
		}
		echo implode( '<br />', $aAnswer );
	}
}
?>

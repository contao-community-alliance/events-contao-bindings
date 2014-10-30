<?php
/**
 * The Contao Community Alliance events-contao-bindings library allows easy use of various Contao classes.
 *
 * PHP version 5
 * @package	ContaoCommunityAlliance\Contao\Bindings\Test
 * @author	 David Molineus <david.molineus@netzmacht.de>
 * @copyright  The Contao Community Alliance
 * @license	LGPL.
 * @filesource
 */

namespace ContaoCommunityAlliance\Contao\Bindings\Test;

use ContaoCommunityAlliance\Contao\Bindings\ContaoEvents;
use ContaoCommunityAlliance\Contao\Bindings\Events\Backend\AddToUrlEvent;
use ContaoCommunityAlliance\Contao\Bindings\Events\Backend\GetThemeEvent;
use ContaoCommunityAlliance\Contao\Bindings\Events\Controller\GenerateFrontendUrlEvent;
use ContaoCommunityAlliance\Contao\Bindings\Events\Controller\GetArticleEvent;
use ContaoCommunityAlliance\Contao\Bindings\Events\Controller\GetContentElementEvent;
use ContaoCommunityAlliance\Contao\Bindings\Events\Controller\GetPageDetailsEvent;
use ContaoCommunityAlliance\Contao\Bindings\Events\Controller\GetTemplateGroupEvent;
use ContaoCommunityAlliance\Contao\Bindings\Events\Controller\ReplaceInsertTagsEvent;
use ContaoCommunityAlliance\Contao\Bindings\Events\Date\ParseDateEvent;
use ContaoCommunityAlliance\Contao\Bindings\Events\Image\GenerateHtmlEvent;
use ContaoCommunityAlliance\Contao\Bindings\Events\Image\ResizeImageEvent;
use ContaoCommunityAlliance\Contao\Bindings\Events\Message\AddMessageEvent;
use ContaoCommunityAlliance\Contao\Bindings\Events\News\GetNewsEvent;
use ContaoCommunityAlliance\Contao\Bindings\Events\System\GetReferrerEvent;
use ContaoCommunityAlliance\Contao\Bindings\Events\Widget\GetAttributesFromDcaEvent;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTestCase;
use ContaoCommunityAlliance\Contao\Bindings\Api;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class ApiTestCase extends ProphecyTestCase
{
	public function test_backend_addToUrl_dispatches_event()
	{
		$this->prophesizeDispatchedEvent(
			ContaoEvents::BACKEND_ADD_TO_URL,
			'ContaoCommunityAlliance\Contao\Bindings\Events\Backend\AddToUrlEvent',
			function(AddToUrlEvent $event){
				$event->setUrl('contao/main.php?act=test');
			}
		);

		$this->assertEquals('contao/main.php?act=test', Api\Backend\addToUrl('act=test'));
	}

	public function test_backend_getTheme_dispatches_event()
	{
		$this->prophesizeDispatchedEvent(
			ContaoEvents::BACKEND_GET_THEME,
			'ContaoCommunityAlliance\Contao\Bindings\Events\Backend\GetThemeEvent',
			function(GetThemeEvent $event){
				$event->setTheme('example');
			}
		);

		$this->assertEquals('example', Api\Backend\getTheme());
	}

	public function test_calender_getCalendarEvent_dispatches_event()
	{
		$this->prophesizeDispatchedEvent(
			ContaoEvents::CALENDAR_GET_EVENT,
		   'ContaoCommunityAlliance\Contao\Bindings\Events\Calendar\GetCalendarEventEvent',
			function($event) {;
				$event->setCalendarEventHtml('html');
			}
		);

		$this->assertEquals('html', Api\Calendar\getCalendarEvent(1));
	}

	public function test_controller_addEnclosureToTemplate_dispatches_event()
	{
		$this->prophesizeDispatchedEvent(
			ContaoEvents::CONTROLLER_ADD_ENCLOSURE_TO_TEMPLATE,
			'ContaoCommunityAlliance\Contao\Bindings\Events\Controller\AddEnclosureToTemplateEvent'
		);

		$this->assertNull(Api\Controller\addEnclosureToTemplate(array('test'), new \stdClass()));
	}

	public function test_controller_addImageToTemplate_dispatches_event()
	{
		$this->prophesizeDispatchedEvent(
			ContaoEvents::CONTROLLER_ADD_IMAGE_TO_TEMPLATE,
			'ContaoCommunityAlliance\Contao\Bindings\Events\Controller\AddImageToTemplateEvent'
		);

		$this->assertNull(Api\Controller\addImageToTemplate(array('test'), new \stdClass()));
	}

	public function test_controller_addToUrl_dispatches_event()
	{
		$this->prophesizeDispatchedEvent(
			ContaoEvents::CONTROLLER_ADD_IMAGE_TO_TEMPLATE,
			'ContaoCommunityAlliance\Contao\Bindings\Events\Controller\AddImageToTemplateEvent'
		);

		$this->assertNull(Api\Controller\addImageToTemplate(array('test'), new \stdClass()));
	}

	public function test_controller_generateFrontendUrl_dispatches_event()
	{
		$this->prophesizeDispatchedEvent(
			ContaoEvents::CONTROLLER_GENERATE_FRONTEND_URL,
			'ContaoCommunityAlliance\Contao\Bindings\Events\Controller\GenerateFrontendUrlEvent',
			function(GenerateFrontendUrlEvent $event) {
				$event->setUrl('my.test.url');
			}
		);

		$this->assertEquals('my.test.url', Api\Controller\generateFrontendUrl(array('page')));
	}

	public function test_controller_getArticle_dispatches_event()
	{
		$this->prophesizeDispatchedEvent(
			ContaoEvents::CONTROLLER_GET_ARTICLE,
			'ContaoCommunityAlliance\Contao\Bindings\Events\Controller\GetArticleEvent',
			function(GetArticleEvent $event) {
				$event->setArticle('article.html');
			}
		);

		$this->assertEquals('article.html', Api\Controller\getArticle(1));
	}

	public function test_controller_getContentElement_dispatches_event()
	{
		$this->prophesizeDispatchedEvent(
			ContaoEvents::CONTROLLER_GET_CONTENT_ELEMENT,
			'ContaoCommunityAlliance\Contao\Bindings\Events\Controller\GetContentElementEvent',
			function(GetContentElementEvent $event) {
				$event->setContentElementHtml('ce.html');
			}
		);

		$this->assertEquals('ce.html', Api\Controller\getContentElement(1));
	}

	public function test_controller_getPageDetails_dispatches_event()
	{
		$this->prophesizeDispatchedEvent(
			ContaoEvents::CONTROLLER_GET_PAGE_DETAILS,
			'ContaoCommunityAlliance\Contao\Bindings\Events\Controller\GetPageDetailsEvent',
			function(GetPageDetailsEvent $event) {
				$event->setPageDetails(array('page' => 'details'));
			}
		);

		$this->assertEquals(array('page' => 'details'), Api\Controller\getPageDetails(1));
	}

	public function test_controller_getTemplateGroup_dispatches_event()
	{
		$this->prophesizeDispatchedEvent(
			ContaoEvents::CONTROLLER_GET_TEMPLATE_GROUP,
			'ContaoCommunityAlliance\Contao\Bindings\Events\Controller\GetTemplateGroupEvent',
			function(GetTemplateGroupEvent $event) {
				$event->getTemplates()->append('test');
			}
		);

		$group = new \ArrayObject(array('test'));
		$this->assertEquals($group, Api\Controller\getTemplateGroup('test'));
	}

	public function test_controller_loadDataContainer_dispatches_event()
	{
		$this->prophesizeDispatchedEvent(
			ContaoEvents::CONTROLLER_LOAD_DATA_CONTAINER,
			'ContaoCommunityAlliance\Contao\Bindings\Events\Controller\LoadDataContainerEvent'
		);

		$this->assertNull(Api\Controller\loadDataContainer('tl_test'));
	}

	public function test_controller_redirect_dispatches_event()
	{
		$this->prophesizeDispatchedEvent(
			ContaoEvents::CONTROLLER_REDIRECT,
			'ContaoCommunityAlliance\Contao\Bindings\Events\Controller\RedirectEvent'
		);

		$this->assertNull(Api\Controller\redirect('new-location'));
	}

	public function test_controller_reload_dispatches_event()
	{
		$this->prophesizeDispatchedEvent(
			ContaoEvents::CONTROLLER_RELOAD,
			'ContaoCommunityAlliance\Contao\Bindings\Events\Controller\ReloadEvent'
		);

		$this->assertNull(Api\Controller\reload());
	}

	public function test_controller_replaceInsertTags_dispatches_event()
	{
		$this->prophesizeDispatchedEvent(
			ContaoEvents::CONTROLLER_REPLACE_INSERT_TAGS,
			'ContaoCommunityAlliance\Contao\Bindings\Events\Controller\ReplaceInsertTagsEvent',
			function(ReplaceInsertTagsEvent $event) {
				$event->setBuffer('test');
			}
		);

		$this->assertEquals('test', Api\Controller\replaceInsertTags('{{test}}'));
	}

	public function test_date_parseDate_dispatches_event()
	{
		$this->prophesizeDispatchedEvent(
			ContaoEvents::DATE_PARSE,
			'ContaoCommunityAlliance\Contao\Bindings\Events\Date\ParseDateEvent',
			function(ParseDateEvent $event) {
				$event->setResult('01.04.2015');
			}
		);

		$this->assertEquals('01.04.2015', Api\Date\parseDate(time()));
	}

	public function test_image_generateHtml_dispatches_event()
	{
		$this->prophesizeDispatchedEvent(
			ContaoEvents::IMAGE_GET_HTML,
			'ContaoCommunityAlliance\Contao\Bindings\Events\Image\GenerateHtmlEvent',
			function(GenerateHtmlEvent $event) {
				$event->setHtml('<img src="test.png">');
			}
		);

		$this->assertEquals('<img src="test.png">', Api\Image\generateHtml('test.png'));
	}

	public function test_image_resizeImage_dispatches_event()
	{
		$this->prophesizeDispatchedEvent(
			ContaoEvents::IMAGE_RESIZE,
			'ContaoCommunityAlliance\Contao\Bindings\Events\Image\ResizeImageEvent',
			function(ResizeImageEvent $event) {
				$event->setResultImage('assets/img/test.png');
			}
		);

		$this->assertEquals('assets/img/test.png', Api\Image\resizeImage('test.png', '200', '100'));
	}

	public function test_message_addMessage_dispatches_event()
	{
		$this->prophesizeAddMessageEvent('test', 'error');

		$this->assertNull(Api\Message\addMessage('test', 'error'));
	}

	public function test_message_addError_dispatches_event()
	{
		$this->prophesizeAddMessageEvent('error message', AddMessageEvent::TYPE_ERROR);

		$this->assertNull(Api\Message\addError('error message'));
	}

	public function test_message_addInfo_dispatches_event()
	{
		$this->prophesizeAddMessageEvent('info message', AddMessageEvent::TYPE_INFO);

		$this->assertNull(Api\Message\addInfo('info message'));
	}

	public function test_message_addConfirm_dispatches_event()
	{
		$this->prophesizeAddMessageEvent('confirm message', AddMessageEvent::TYPE_CONFIRM);

		$this->assertNull(Api\Message\addConfirm('confirm message'));
	}

	public function test_message_addNew_dispatches_event()
	{
		$this->prophesizeAddMessageEvent('new message', AddMessageEvent::TYPE_NEW);

		$this->assertNull(Api\Message\addNew('new message'));
	}

	public function test_message_addRaw_dispatches_event()
	{
		$this->prophesizeAddMessageEvent('raw message', AddMessageEvent::TYPE_RAW);

		$this->assertNull(Api\Message\addRaw('raw message'));
	}

	public function test_news_getNews_dispatches_event()
	{
		$this->prophesizeDispatchedEvent(
			ContaoEvents::NEWS_GET_NEWS,
			'ContaoCommunityAlliance\Contao\Bindings\Events\News\GetNewsEvent',
			function(GetNewsEvent $event) {
				$event->setNewsHtml('news.html');
			}
		);

		$this->assertEquals('news.html', Api\News\getNews(1));
	}

	public function test_system_getReferrer_dispatches_event()
	{
		$this->prophesizeDispatchedEvent(
			ContaoEvents::SYSTEM_GET_REFERRER,
			'ContaoCommunityAlliance\Contao\Bindings\Events\System\GetReferrerEvent',
			function(GetReferrerEvent $event) {
				$event->setReferrerUrl('referrer.url');
			}
		);

		$this->assertEquals('referrer.url', Api\System\getReferrer());
	}

	public function test_system_loadLanguageFile_dispatches_event()
	{
		$this->prophesizeDispatchedEvent(
			ContaoEvents::SYSTEM_LOAD_LANGUAGE_FILE,
			'ContaoCommunityAlliance\Contao\Bindings\Events\System\LoadLanguageFileEvent'
		);

		$this->assertNull(Api\System\loadLanguageFile('tl_test'));
	}

	public function test_system_log_dispatches_event()
	{
		$this->prophesizeDispatchedEvent(
			ContaoEvents::SYSTEM_LOG,
			'ContaoCommunityAlliance\Contao\Bindings\Events\System\LogEvent'
		);

		$this->assertNull(Api\System\log('test', __METHOD__, 'test'));
	}

	public function test_widget_getAttributesFromDca_dispatches_event()
	{
		$this->prophesizeDispatchedEvent(
			ContaoEvents::WIDGET_GET_ATTRIBUTES_FROM_DCA,
			'ContaoCommunityAlliance\Contao\Bindings\Events\Widget\GetAttributesFromDcaEvent',
			function(GetAttributesFromDcaEvent $event) {
				$event->setResult(array('test' => 'result'));
			}
		);

		$this->assertEquals(
			array('test' => 'result'),
			Api\Widget\getAttributesFromDca(
				array('field' => 'config'),
				'test'
			)
		);
	}

	private function prophesizeDispatchedEvent($eventName, $eventClass, $listener = null)
	{
		$test = $this;

		$dispatcher = $this->prophesize('Symfony\Component\EventDispatcher\EventDispatcherInterface');

		$dispatcher
			->dispatch($eventName, Argument::type($eventClass))
			->shouldBeCalled();

		$dispatcher
			->dispatch($eventName, Argument::type($eventClass))
			->will(function($args) use($test, $eventName, $eventClass, $listener) {
					$test->assertEquals($eventName, $args[0]);
					$test->assertInstanceOf($eventClass, $args[1]);

					if ($listener) {
						call_user_func($listener, $args[1]);
					}
				}
			);

		$GLOBALS['container']['event-dispatcher'] = $dispatcher->reveal();
	}

	private function prophesizeAddMessageEvent($content, $type)
	{
		$test = $this;
		$this->prophesizeDispatchedEvent(
			ContaoEvents::MESSAGE_ADD,
			'ContaoCommunityAlliance\Contao\Bindings\Events\Message\AddMessageEvent',
			function(AddMessageEvent $event) use ($test, $content, $type) {
				$test->assertEquals($event->getContent(), $content);
				$test->assertEquals($event->getType(), $type);
			}
		);
	}
}

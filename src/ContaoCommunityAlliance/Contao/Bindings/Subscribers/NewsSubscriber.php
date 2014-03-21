<?php
/**
 * The Contao Community Alliance events-contao-bindings library allows easy use of various Contao classes.
 *
 * PHP version 5
 * @package    ContaoCommunityAlliance\Contao\Bindings
 * @subpackage Subscribers
 * @author     Christian Schiffler <c.schiffler@cyberspectrum.de>
 * @copyright  The Contao Community Alliance
 * @license    LGPL.
 * @filesource
 */

namespace ContaoCommunityAlliance\Contao\Bindings\Subscribers;

use ContaoCommunityAlliance\Contao\Bindings\ContaoEvents;
use ContaoCommunityAlliance\Contao\Bindings\Events\Controller\AddEnclosureToTemplateEvent;
use ContaoCommunityAlliance\Contao\Bindings\Events\Controller\AddImageToTemplateEvent;
use ContaoCommunityAlliance\Contao\Bindings\Events\Controller\GenerateFrontendUrlEvent;
use ContaoCommunityAlliance\Contao\Bindings\Events\News\GetNewsEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Subscriber for the news extension.
 */
class NewsSubscriber
	extends \ModuleNews
	implements EventSubscriberInterface
{
	/**
	 * Returns an array of event names this subscriber wants to listen to.
	 *
	 * @return array
	 */
	public static function getSubscribedEvents()
	{
		return array(
			ContaoEvents::NEWS_GET_NEWS => 'handleNews',
		);
	}

	/**
	 * Constructor - this one does NOT call parent constructor to have overhead minimal.
	 */
	public function __construct()
	{
		// Do not call parent constructor.
		$this->import('Config');
		$this->import('Input');
		$this->import('Environment');
		$this->import('Session');
		$this->import('Database');
	}

	/**
	 * Empty override to make class non abstract.
	 *
	 * @return string
	 */
	public function compile()
	{
		return '';
	}

	/**
	 * Render a news.
	 *
	 * @param GetNewsEvent $event The event.
	 *
	 * @return void
	 */
	public function handleNews(GetNewsEvent $event)
	{
		if ($event->getNewsHtml())
		{
			return;
		}

		$eventDispatcher = $event->getDispatcher();

		$time = time();

		// Get news item.
		$objArticle = $this->Database->prepare(
			'SELECT
				*,
				author AS authorId,
				(SELECT title FROM tl_news_archive WHERE tl_news_archive.id=tl_news.pid) AS archive,
				(SELECT jumpTo FROM tl_news_archive WHERE tl_news_archive.id=tl_news.pid) AS parentJumpTo,
				(SELECT name FROM tl_user WHERE id=author) AS author
			FROM tl_news
			WHERE
				pid IN(' . implode(',', array_map('intval', $this->news_archives)) . ')
				AND (id=? OR alias=?)' .
				(!BE_USER_LOGGED_IN
					? ' AND (start=\'\' OR start<?) AND (stop=\'\' OR stop>?) AND published=1'
					: ''
				)
			)
			->limit(1)
			->execute(
				$event->getNewsId(),
				$event->getNewsId(),
				$time,
				$time
			);

		if ($objArticle->numRows < 1)
		{
			return;
		}

		$objPage = $this->getPageDetails($objArticle->jumpTo);

		$objTemplate = new \FrontendTemplate($event->getTemplate());
		$objTemplate->setData($objArticle->row());

		$objTemplate->class          = (($objArticle->cssClass != '') ? ' ' . $objArticle->cssClass : '');
		$objTemplate->newsHeadline   = $objArticle->headline;
		$objTemplate->subHeadline    = $objArticle->subheadline;
		$objTemplate->hasSubHeadline = $objArticle->subheadline ? true : false;
		$objTemplate->linkHeadline   = $this->generateLinkForEvent($eventDispatcher, $objArticle->headline, $objArticle);
		$objTemplate->more           = $this->generateLinkForEvent(
			$eventDispatcher,
			$GLOBALS['TL_LANG']['MSC']['more'],
			$objArticle,
			false,
			true
		);
		$objTemplate->link           = $this->generateNewsUrlForEvent($eventDispatcher, $objArticle);
		$objTemplate->archive        = $objArticle->archive;
		$objTemplate->count          = 0;
		$objTemplate->text           = '';

		// Clean the RTE output.
		if ($objArticle->teaser != '')
		{
			if ($objPage->outputFormat == 'xhtml')
			{
				$objArticle->teaser = $this->String->toXhtml($objArticle->teaser);
			}
			else
			{
				$objArticle->teaser = $this->String->toHtml5($objArticle->teaser);
			}

			$objTemplate->teaser = $this->String->encodeEmail($objArticle->teaser);
		}

		// Display the "read more" button for external/article links.
		if ($objArticle->source != 'default' && $objArticle->text == '')
		{
			$objTemplate->text = true;
		}
		// Encode e-mail addresses.
		else
		{
			// Clean the RTE output.
			if ($objPage->outputFormat == 'xhtml')
			{
				$objArticle->text = $this->String->toXhtml($objArticle->text);
			}
			else
			{
				$objArticle->text = $this->String->toHtml5($objArticle->text);
			}

			$objTemplate->text = $this->String->encodeEmail($objArticle->text);
		}

		$arrMeta = $this->getMetaFieldsForEvent($objArticle);

		// Add meta information.
		$objTemplate->date             = $arrMeta['date'];
		$objTemplate->hasMetaFields    = !empty($arrMeta);
		$objTemplate->numberOfComments = $arrMeta['ccount'];
		$objTemplate->commentCount     = $arrMeta['comments'];
		$objTemplate->timestamp        = $objArticle->date;
		$objTemplate->author           = $arrMeta['author'];
		$objTemplate->datetime         = date('Y-m-d\TH:i:sP', $objArticle->date);
		$objTemplate->addImage         = false;

		// Add an image.
		if ($objArticle->addImage && is_file(TL_ROOT . '/' . $objArticle->singleSRC))
		{
			// Do not override the field now that we have a model registry (see #6303).
			$arrArticle = $objArticle->row();

			// Override the default image size.
			// FIXME: this is always false!
			if ($this->imgSize != '')
			{
				$size = deserialize($this->imgSize);

				if ($size[0] > 0 || $size[1] > 0)
				{
					$arrArticle['size'] = $this->imgSize;
				}
			}

			$arrArticle['singleSRC'] = $objArticle->path;

			$addImageToTemplateEvent = new AddImageToTemplateEvent($arrArticle, $objTemplate);

			$eventDispatcher->dispatch(ContaoEvents::CONTROLLER_ADD_IMAGE_TO_TEMPLATE, $addImageToTemplateEvent);
		}

		$objTemplate->enclosure = array();

		$objTemplate->enclosure = array();

		// Add enclosures.
		if ($objArticle->addEnclosure)
		{
			$addEnclosureToTemplateEvent = new AddEnclosureToTemplateEvent($objArticle->row(), $objTemplate);

			$eventDispatcher->dispatch(ContaoEvents::CONTROLLER_ADD_ENCLOSURE_TO_TEMPLATE, $addEnclosureToTemplateEvent);
		}

		$news = $objTemplate->parse();
		$event->setNewsHtml($news);
	}


	/**
	 * Return the meta fields of a news article as array.
	 *
	 * @param \Database_Result $objArticle The model.
	 *
	 * @return array
	 */
	protected function getMetaFieldsForEvent($objArticle)
	{
		$meta = deserialize($this->news_metaFields);

		if (!is_array($meta))
		{
			return array();
		}

		$return = array();

		foreach ($meta as $field)
		{
			switch ($field)
			{
				case 'date':
					$return['date'] = $this->parseDate($GLOBALS['objPage']->datimFormat, $objArticle->date);
					break;

				case 'author':
					if ($objArticle->author != '')
					{
						$return['author'] = $GLOBALS['TL_LANG']['MSC']['by'] . ' ' . $objArticle->author;
					}
					break;

				case 'comments':
					if ($objArticle->noComments || $objArticle->source != 'default')
					{
						break;
					}

					$objComments = $this->Database->prepare('SELECT
							COUNT(*) AS total
							FROM tl_comments
							WHERE source=\'tl_news\'
							AND parent=?' .
							(!BE_USER_LOGGED_IN ? ' AND published=1' : '')
						)
						->execute($objArticle->id);

					if ($objComments->numRows)
					{
						$return['ccount']   = $objComments->total;
						$return['comments'] = sprintf($GLOBALS['TL_LANG']['MSC']['commentCount'], $objComments->total);
					}
					break;
				default:
			}
		}

		return $return;
	}


	/**
	 * Generate a URL and return it as string.
	 *
	 * @param EventDispatcherInterface $eventDispatcher The event dispatcher.
	 *
	 * @param \Database_Result         $objArticle      The news model.
	 *
	 * @param boolean                  $blnAddArchive   Add the current archive parameter (news archive) (default: false).
	 *
	 * @return string
	 */
	protected function generateNewsUrlForEvent(
		EventDispatcherInterface $eventDispatcher,
		\Database_Result $objArticle,
		$blnAddArchive = false
	)
	{
		$url = null;

		switch ($objArticle->source)
		{
			// Link to an external page.
			case 'external':
				$this->import('String');

				if (substr($objArticle->url, 0, 7) == 'mailto:')
				{
					$url = $this->String->encodeEmail($objArticle->url);
				}
				else
				{
					$url = ampersand($objArticle->url);
				}
				break;

			// Link to an internal page.
			case 'internal':
				$objPage = $this->Database->prepare('SELECT id, alias FROM tl_page WHERE id=?')
					->limit(1)
					->execute($objArticle->jumpTo);

				if ($objPage->numRows)
				{
					$url = ampersand($this->generateFrontendUrl($objPage->row()));
				}
				break;

			// Link to an article.
			case 'article':
				$objPage = $this->Database->prepare('SELECT
						a.id AS aId,
						a.alias AS aAlias,
						a.title,
						p.id,
						p.alias
					FROM tl_article a,
						tl_page p
					WHERE
						a.pid=p.id
					AND a.id=?'
					)
					->limit(1)
					->execute($objArticle->articleId);

				if ($objPage->numRows)
				{
					$generateFrontendUrlEvent = new GenerateFrontendUrlEvent(
						$objPage->row(),
						'/articles/' . (
						(!$GLOBALS['TL_CONFIG']['disableAlias'] && $objPage->aAlias != '')
							? $objPage->aAlias
							: $objPage->aId)
					);

					$eventDispatcher->dispatch(ContaoEvents::CONTROLLER_GENERATE_FRONTEND_URL, $generateFrontendUrlEvent);

					$url = $generateFrontendUrlEvent->getUrl();
				}
				break;
			default:
		}

		// Link to the default page.
		if ($url == '')
		{
			$objPage = $this->Database->prepare('SELECT id, alias FROM tl_page WHERE id=?')
				->limit(1)
				->execute($objArticle->parentJumpTo);

			if ($objPage->numRows)
			{
				$generateFrontendUrlEvent = new GenerateFrontendUrlEvent(
					$objPage->row(),
					(
						($GLOBALS['TL_CONFIG']['useAutoItem'] ?  '/' : '/items/') .
						((!$GLOBALS['TL_CONFIG']['disableAlias'] && $objArticle->alias != '')
							? $objArticle->alias
							: $objArticle->id)
					)
				);

				$eventDispatcher->dispatch(ContaoEvents::CONTROLLER_GENERATE_FRONTEND_URL, $generateFrontendUrlEvent);

				$url = $generateFrontendUrlEvent->getUrl();
			}
			else
			{
				$url = ampersand($this->Environment->request, true);
			}

			// Add the current archive parameter (news archive).
			if ($blnAddArchive && $this->Input->get('month') != '')
			{
				$url .= ($GLOBALS['TL_CONFIG']['disableAlias'] ? '&amp;' : '?') . 'month=' . $this->Input->get('month');
			}
		}

		return $url;
	}

	/**
	 * Generate a link and return it as string.
	 *
	 * @param EventDispatcherInterface $eventDispatcher The event dispatcher.
	 *
	 * @param string                   $strLink         The link text.
	 *
	 * @param \Database_Result         $objArticle      The model.
	 *
	 * @param bool                     $blnAddArchive   Add the current archive parameter (news archive) (default: false).
	 *
	 * @param bool                     $blnIsReadMore   Determine if the link is a "read more" link.
	 *
	 * @return string
	 */
	protected function generateLinkForEvent(
		EventDispatcherInterface $eventDispatcher,
		$strLink,
		$objArticle,
		$blnAddArchive = false,
		$blnIsReadMore = false
	)
	{
		// Internal link.
		if ($objArticle->source != 'external')
		{
			return sprintf('<a href="%s" title="%s">%s%s</a>',
							$this->generateNewsUrlForEvent($eventDispatcher, $objArticle, $blnAddArchive),
							specialchars(sprintf($GLOBALS['TL_LANG']['MSC']['readMore'], $objArticle->headline), true),
							$strLink,
							($blnIsReadMore ? ' <span class="invisible">'.$objArticle->headline.'</span>' : ''));
		}

		// Encode e-mail addresses.
		if (substr($objArticle->url, 0, 7) == 'mailto:')
		{
			$this->import('String');
			$strArticleUrl = $this->String->encodeEmail($objArticle->url);
		}

		// Ampersand URIs.
		else
		{
			$strArticleUrl = ampersand($objArticle->url);
		}

		// External link.
		return sprintf('<a href="%s" title="%s"%s>%s</a>',
						$strArticleUrl,
						specialchars(sprintf($GLOBALS['TL_LANG']['MSC']['open'], $strArticleUrl)),
						$objArticle->target
							? (
							($GLOBALS['objPage']->outputFormat == 'xhtml')
								? ' onclick="return !window.open(this.href)"'
								: ' target="_blank"'
							)
							: '',
						$strLink);
	}
}

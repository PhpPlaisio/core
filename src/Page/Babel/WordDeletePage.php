<?php
declare(strict_types=1);

namespace Plaisio\Core\Page\Babel;

use Plaisio\C;
use Plaisio\Kernel\Nub;
use Plaisio\Response\Response;
use Plaisio\Response\SeeOtherResponse;

/**
 * Page for deleting a word.
 */
class WordDeletePage extends BabelPage
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The ID of the word.
   *
   * @var int
   */
  private int $wrdId;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   */
  public function __construct()
  {
    parent::__construct();

    $this->wrdId = Nub::$nub->cgi->getManId('wrd', 'wrd');
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the relative URL for this page.
   *
   * @param int $wrdId The ID of the word to be deleted.
   *
   * @return string
   */
  public static function getUrl(int $wrdId): string
  {
    $url = Nub::$nub->cgi->putLeader();
    $url .= Nub::$nub->cgi->putId('pag', C::PAG_ID_BABEL_WORD_DELETE, 'pag');
    $url .= Nub::$nub->cgi->putId('wrd', $wrdId, 'wrd');

    return $url;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  public function handleRequest(): Response
  {
    $details = Nub::$nub->DL->abcBabelWordGetDetails($this->wrdId, $this->lanId);

    Nub::$nub->DL->abcBabelWordDeleteWord($this->wrdId);

    $this->response = new SeeOtherResponse(WordGroupDetailsPage::getUrl($details['wdg_id'], $this->actLanId));

    return $this->response;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Echos the actual page content.
   *
   * @return void
   */
  protected function echoTabContent(): void
  {
    // Nothing to do.
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------

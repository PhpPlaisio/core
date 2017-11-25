<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Core\Page\Babel;

use SetBased\Abc\Abc;
use SetBased\Abc\C;
use SetBased\Abc\Helper\HttpHeader;

//----------------------------------------------------------------------------------------------------------------------
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
  private $wrdId;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   */
  public function __construct()
  {
    parent::__construct();

    $this->wrdId = self::getCgiId('wrd', 'wrd');
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the relative URL for this page.
   *
   * @param int $wrdId The ID of the word to be deleted.
   *
   * @return string
   */
  public static function getUrl($wrdId)
  {
    $url = self::putCgiId('pag', C::PAG_ID_BABEL_WORD_DELETE, 'pag');
    $url .= self::putCgiId('wrd', $wrdId, 'wrd');

    return $url;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * {@inheritdoc}
   */
  protected function echoTabContent()
  {
    $details = Abc::$DL->abcBabelWordGetDetails($this->wrdId, $this->lanId);

    Abc::$DL->abcBabelWordDeleteWord($this->wrdId);

    HttpHeader::redirectSeeOther(WordGroupDetailsPage::getUrl($details['wdg_id'], $this->actLanId));
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------

<?php
declare(strict_types=1);

namespace Plaisio\Core\TableColumn\Babel;

use Plaisio\Core\Page\Babel\WordTranslatePage;
use Plaisio\Core\TableColumn\IconTableColumn;

/**
 * Table column with icon linking to page for translating a single word.
 */
class WordTranslateIconTableColumn extends IconTableColumn
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The ID of the target language.
   *
   * @var int
   */
  private int $lanIdTar;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   *
   * @param int $lanIdTar The ID of the target language.
   */
  public function __construct(int $lanIdTar)
  {
    parent::__construct();

    $this->lanIdTar = $lanIdTar;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  public function getClasses(array $row): array
  {
    return ['icons-small', 'icons-small-babel-fish'];
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  public function getUrl(array $row): ?string
  {
    return WordTranslatePage::getUrl($row['wrd_id'], $this->lanIdTar);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------

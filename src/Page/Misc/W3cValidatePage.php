<?php

namespace SetBased\Abc\Core\Page\Misc;

use Gajus\Dindent\Indenter;
use SetBased\Abc\Abc;
use SetBased\Abc\C;
use SetBased\Abc\Exception\NotAuthorizedException;
use SetBased\Abc\Helper\Html;
use SetBased\Abc\Page\CorePage;
use SetBased\Exception\FallenException;

/**
 * Page for validating the generated HTML code. This page must be use on development environments only.
 */
class W3cValidatePage extends CorePage
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The path to the ca-bundle.crt file.
   *
   * @var string
   */
  private $caBundlePath = '/etc/pki/tls/certs/ca-bundle.crt';

  /**
   * The basename of the temporary file with the HTML code which must be validated.
   *
   * @var string
   */
  private $filename;

  /**
   * The mode of this page:
   * * validate: shows a HTML snippet indicating the validity of the source.
   * * source: shows the validation report including source listing.
   *
   * @var string
   */
  private $mode;

  /**
   * The path to the temporary file with the HTML code which must be validated.
   *
   * @var string
   */
  private $pathName;

  /**
   * The (base) URL where the W3C Markup Validator is installed.
   *
   * @var string
   */
  private $validatorUrl = 'https://validator.setbased.nl/w3c-validator/';

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   */
  public function __construct()
  {
    parent::__construct();

    $this->filename = Abc::$cgi->getManString('file');
    $this->mode     = Abc::$cgi->getManString('mode');

    $this->pathName = DIR_TMP.'/'.$this->filename;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Return the URL of this page.
   *
   * @param string $fileName The name fo the file that must be validated.
   * @param string $mode     Either 'validate', or 'source'.
   *
   * @return string The URL of this page.
   */
  public static function getUrl(string $fileName, string $mode = 'validate'): string
  {
    $url = Abc::$cgi->putLeader();
    $url .= Abc::$cgi->putId('pag', C::PAG_ID_MISC_W3C_VALIDATE, 'pag');
    $url .= Abc::$cgi->putString('mode', $mode);
    $url .= Abc::$cgi->putString('file', $fileName);

    return $url;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Validates that the requested file is a file for W3C validation and is owned by the current user.
   */
  public function checkAuthorization(): void
  {
    // Assert that the filename is a basename (and does not contain crafted (sub-)directories).
    if (basename($this->filename)!==$this->filename)
    {
      throw new NotAuthorizedException("Filename '%s' is not a basename.", $this->filename);
    }

    $prefix = 'w3c_validator_'.Abc::obfuscate($this->usrId, 'usr').'_';
    if (strncmp($this->filename, $prefix, strlen($prefix))!==0)
    {
      throw new NotAuthorizedException("Filename '%s' is not a file for W3C validation owned by the current user.",
                                       $this->filename);
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  public function echoPage(): void
  {
    switch ($this->mode)
    {
      case 'validate':
        $this->showValidateResponse();
        break;

      case 'source':
        $this->showValidateSource();
        break;

      default:
        throw new FallenException('mode', $this->mode);
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Shows a HTML snippet indicating the validity of the source.
   */
  private function showValidateResponse(): void
  {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_HEADER, 1);
    curl_setopt($ch, CURLOPT_VERBOSE, 0);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible;)');
    curl_setopt($ch, CURLOPT_URL, $this->validatorUrl.'check');
    curl_setopt($ch, CURLOPT_POST, true);

    if (class_exists('\\CURLFile'))
    {
      // PHP 5.5 and higher.
      $file = new \CURLFile($this->pathName, 'text/html', $this->pathName);
    }
    else
    {
      // PHP 5.4.
      $file = '@'.$this->pathName.';type=text/html';
    }
    $post                  = [];
    $post['uploaded_file'] = $file;
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
    curl_setopt($ch, CURLOPT_CAPATH, $this->caBundlePath);

    $response = curl_exec($ch);

    if (strpos($response, 'X-W3C-Validator-Status: Valid')>0)
    {
      echo 'xhtml: OK';

      // The HTML is valid. Remove the temporary file.
      unlink($this->pathName);
    }
    elseif (strpos($response, 'X-W3C-Validator-Status: Invalid')>0)
    {
      $url = self::getUrl($this->filename, 'source');
      echo '<a', Html::generateAttribute('href', $url),
      ' target="_blank" class="w3c_validator_status_invalid">w3c validate</a>';
    }
    else
    {
      echo 'xhtml: Error';
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Shows the validation report including source listing.
   */
  private function showValidateSource(): void
  {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_HEADER, 1);
    curl_setopt($ch, CURLOPT_VERBOSE, 0);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible;)');
    curl_setopt($ch, CURLOPT_URL, $this->validatorUrl.'check');
    curl_setopt($ch, CURLOPT_POST, true);

    try
    {
      $indenter = new Indenter(['indentation_character' => '  ']);
      file_put_contents($this->pathName, $indenter->indent(file_get_contents($this->pathName)));
    }
    catch (\Exception $e)
    {
      // Indenter is a memory hork and might consume too much memory.
      file_put_contents($this->pathName, file_get_contents($this->pathName));
    };

    if (class_exists('\\CURLFile'))
    {
      // PHP 5.5 and higher.
      $file = new \CURLFile($this->pathName, 'text/html', $this->pathName);
    }
    else
    {
      // PHP 5.4.
      $file = $this->pathName.';type=text/html';
    }
    $post                  = [];
    $post['uploaded_file'] = $file;
    $post['ss']            = '1';

    curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
    curl_setopt($ch, CURLOPT_CAPATH, $this->caBundlePath);

    $response = curl_exec($ch);

    $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
    $body        = substr($response, $header_size);

    $body = preg_replace("/(href=|src=|@import\\s)(['\"])([^#:'\"]*)(['\"]|(?:(?:%20|\\s|\\+)[^'\"]*))/",
                         '$1$2'.$this->validatorUrl.'$3$4',
                         $body);

    echo $body;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------


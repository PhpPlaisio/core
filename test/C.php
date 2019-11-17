<?php

namespace Plaisio;

class C
{
  const LAN_ID_BABEL_REFERENCE = 0;
  const OPEN_DATE_STOP         = '9999-12-31';

  const LGR_ID_BLOCKED        = 0;
  const LGR_ID_GRANTED        = 0;
  const LGR_ID_NO_ROLE        = 0;
  const LGR_ID_UNKNOWN_USER   = 0;
  const LGR_ID_WRONG_PASSWORD = 0;

  const PAG_ID_MISC_W3C_VALIDATE = 0;
  const LEN_PASSWORD = 0;

  // grep C:: `find src -name "*.php"` | awk 'match($0, /C::[A-Z_]+/) {print "const " substr($0, RSTART+3, RLENGTH-3) " = 0;"}'|sort -u
  const PAG_ID_BABEL_WORD_DELETE = 0;
  const PAG_ID_BABEL_WORD_GROUP_DETAILS = 0;
  const PAG_ID_BABEL_WORD_GROUP_INSERT = 0;
  const PAG_ID_BABEL_WORD_GROUP_OVERVIEW = 0;
  const PAG_ID_BABEL_WORD_GROUP_UPDATE = 0;
  const PAG_ID_BABEL_WORD_INSERT = 0;
  const PAG_ID_BABEL_WORD_TRANSLATE = 0;
  const PAG_ID_BABEL_WORD_TRANSLATE_WORDS = 0;
  const PAG_ID_BABEL_WORD_UPDATE = 0;
  const PAG_ID_COMPANY_DETAILS = 0;
  const PAG_ID_COMPANY_INSERT = 0;
  const PAG_ID_COMPANY_MODULE_OVERVIEW = 0;
  const PAG_ID_COMPANY_MODULE_UPDATE = 0;
  const PAG_ID_COMPANY_OVERVIEW = 0;
  const PAG_ID_COMPANY_ROLE_DETAILS = 0;
  const PAG_ID_COMPANY_ROLE_INSERT = 0;
  const PAG_ID_COMPANY_ROLE_OVERVIEW = 0;
  const PAG_ID_COMPANY_ROLE_UPDATE = 0;
  const PAG_ID_COMPANY_ROLE_UPDATE_FUNCTIONALITIES = 0;
  const PAG_ID_COMPANY_SPECIFIC_PAGE_DELETE = 0;
  const PAG_ID_COMPANY_SPECIFIC_PAGE_INSERT = 0;
  const PAG_ID_COMPANY_SPECIFIC_PAGE_OVERVIEW = 0;
  const PAG_ID_COMPANY_SPECIFIC_PAGE_UPDATE = 0;
  const PAG_ID_COMPANY_UPDATE = 0;
  const PAG_ID_MISC_LOGIN = 0;
  const PAG_ID_MISC_LOGOUT = 0;
  const PAG_ID_MISC_W = 0;
  const PAG_ID_SYSTEM_FUNCTIONALITY_DETAILS = 0;
  const PAG_ID_SYSTEM_FUNCTIONALITY_INSERT = 0;
  const PAG_ID_SYSTEM_FUNCTIONALITY_OVERVIEW = 0;
  const PAG_ID_SYSTEM_FUNCTIONALITY_UPDATE = 0;
  const PAG_ID_SYSTEM_FUNCTIONALITY_UPDATE_PAGES = 0;
  const PAG_ID_SYSTEM_FUNCTIONALITY_UPDATE_ROLES = 0;
  const PAG_ID_SYSTEM_MENU_INSERT = 0;
  const PAG_ID_SYSTEM_MENU_MODIFY = 0;
  const PAG_ID_SYSTEM_MENU_OVERVIEW = 0;
  const PAG_ID_SYSTEM_MODULE_DETAILS = 0;
  const PAG_ID_SYSTEM_MODULE_INSERT = 0;
  const PAG_ID_SYSTEM_MODULE_OVERVIEW = 0;
  const PAG_ID_SYSTEM_MODULE_UPDATE = 0;
  const PAG_ID_SYSTEM_MODULE_UPDATE_COMPANIES = 0;
  const PAG_ID_SYSTEM_PAGE_DETAILS = 0;
  const PAG_ID_SYSTEM_PAGE_INSERT = 0;
  const PAG_ID_SYSTEM_PAGE_OVERVIEW = 0;
  const PAG_ID_SYSTEM_PAGE_UPDATE = 0;
  const PAG_ID_SYSTEM_PAGE_UPDATE_FUNCTIONALITIES = 0;
  const PAG_ID_SYSTEM_ROLE_GROUP_DETAILS = 0;
  const PAG_ID_SYSTEM_ROLE_GROUP_INSERT = 0;
  const PAG_ID_SYSTEM_ROLE_GROUP_OVERVIEW = 0;
  const PAG_ID_SYSTEM_ROLE_GROUP_UPDATE = 0;
  const PAG_ID_SYSTEM_TAB_DETAILS = 0;
  const PAG_ID_SYSTEM_TAB_INSERT = 0;
  const PAG_ID_SYSTEM_TAB_OVERVIEW = 0;
  const PAG_ID_SYSTEM_TAB_UPDATE = 0;
  const WDG_ID_FUNCTIONALITIES = 0;
  const WDG_ID_MENU = 0;
  const WDG_ID_MODULE = 0;
  const WDG_ID_PAGE_GROUP_TITLE = 0;
  const WDG_ID_PAGE_TITLE = 0;
  const WDG_ID_ROLE_GROUP = 0;
  const WRD_ID_BUTTON_INSERT = 0;
  const WRD_ID_BUTTON_LOGIN = 0;
  const WRD_ID_BUTTON_OK = 0;
  const WRD_ID_BUTTON_TRANSLATE = 0;
  const WRD_ID_BUTTON_UPDATE = 0;
  const WRD_ID_COMPANY = 0;
  const WRD_ID_LANGUAGE = 0;
  const WRD_ID_ROLE = 0;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @setbased.stratum.constants
   *
   * Below this doc block constants will be inserted by PhpStratum.
   */
  const LEN_CMP_ABBR          =  15;
  const LEN_CMP_LABEL         =  20;
  const LEN_MNU_GROUP         =   5;
  const LEN_MNU_LEVEL         =   3;
  const LEN_MNU_LINK          =  64;
  const LEN_MNU_WEIGHT        =   5;
  const LEN_PAG_ALIAS         =  32;
  const LEN_PAG_CLASS         = 128;
  const LEN_PAG_LABEL         = 128;
  const LEN_PAG_WEIGHT        =  10;
  const LEN_PTB_LABEL         =  30;
  const LEN_RLG_LABEL         =  50;
  const LEN_RLG_WEIGHT        =   5;
  const LEN_ROL_LABEL         =  50;
  const LEN_ROL_NAME          =  32;
  const LEN_ROL_WEIGHT        =   5;
  const LEN_USR_NAME          =  64;
  const LEN_USR_PASSWORD_HASH =  60;
  const LEN_WDG_LABEL         =  30;
  const LEN_WDG_NAME          =  32;
  const LEN_WDT_TEXT          =  80;
  const LEN_WRD_COMMENT       = 255;
  const LEN_WRD_LABEL         =  50;

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------

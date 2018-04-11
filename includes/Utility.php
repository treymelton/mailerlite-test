<?php
/*******************************************************
* @ brief Utility class for all Basic reused functions
*  @param: take sno parameters
*  @Requires:
*    -BaseClass.php
*
****************************************************/
require_once(dirname(__FILE__).DIRECTORY_SEPARATOR.'BaseClass.php');
class Utility extends BaseClass {
 var $States = array();//
 var $Country = array();//
  public static function Get(){
		//==== instantiate or retrieve singleton ====
		static $inst = NULL;
		if( $inst == NULL )
			$inst = new Utility();
		return( $inst );
   }

   public function __construct(){
    // construct here
   }

  /**
  * get an array of alphabetic strings for character creation
  * @param $intOption - return a specific option or all of them
  * @return string || array
  */
  function MakeAlphabeticStringArray($intOption=0){
     $arrAlphabeticChars = array();
     $arrAlphabeticChars[0] = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ-_!@#$%&*()123456789?<>+=';
     $arrAlphabeticChars[1] = 'abcdefghijklmnopqrstuvwxyz-_!@#$%&*()123456789?<>+=';
     $arrAlphabeticChars[2] = '-_!@#$%&*()123456789?<>+=';
     $arrAlphabeticChars[3] = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
     $arrAlphabeticChars[4] = 'abcdefghijklmnopqrstuvwxyz';
     $arrAlphabeticChars[5] = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
     $arrAlphabeticChars[6] = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
     $arrAlphabeticChars[7] = '1234567890';
     $arrAlphabeticChars[8] = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
     return $arrAlphabeticChars[$intOption];
  }

  /**
  * make a static states array
  * @return array
  */
  public static function MakeStatesArray(){
    $arrStates = array( 'AL'=>"Alabama",
            			'AK'=>"Alaska",
            			'AZ'=>"Arizona",
            			'AR'=>"Arkansas",
            			'CA'=>"California",
            			'CO'=>"Colorado",
            			'CT'=>"Connecticut",
            			'DE'=>"Delaware",
            			'DC'=>"District Of Columbia",
            			'FL'=>"Florida",
            			'GA'=>"Georgia",
            			'HI'=>"Hawaii",
            			'ID'=>"Idaho",
            			'IL'=>"Illinois",
            			'IN'=>"Indiana",
            			'IA'=>"Iowa",
            			'KS'=>"Kansas",
            			'KY'=>"Kentucky",
            			'LA'=>"Louisiana",
            			'ME'=>"Maine",
            			'MD'=>"Maryland",
            			'MA'=>"Massachusetts",
            			'MI'=>"Michigan",
            			'MN'=>"Minnesota",
            			'MS'=>"Mississippi",
            			'MO'=>"Missouri",
            			'MT'=>"Montana",
            			'NE'=>"Nebraska",
            			'NV'=>"Nevada",
            			'NH'=>"New Hampshire",
            			'NJ'=>"New Jersey",
            			'NM'=>"New Mexico",
            			'NY'=>"New York",
            			'NC'=>"North Carolina",
            			'ND'=>"North Dakota",
            			'OH'=>"Ohio",
            			'OK'=>"Oklahoma",
            			'OR'=>"Oregon",
            			'PA'=>"Pennsylvania",
            			'RI'=>"Rhode Island",
            			'SC'=>"South Carolina",
            			'SD'=>"South Dakota",
            			'TN'=>"Tennessee",
            			'TX'=>"Texas",
            			'UT'=>"Utah",
            			'VT'=>"Vermont",
            			'VA'=>"Virginia",
            			'WA'=>"Washington",
            			'WV'=>"West Virginia",
            			'WI'=>"Wisconsin",
            			'WY'=>"Wyoming");
    return $arrStates;
  }

  /**
  * make a static array of countries
  * @return array
  */
  public static function MakeCountryArray(){
    //get countries http://snippets.dzone.com/posts/show/6623
    $arrCountries = array("GB" => "United Kingdom",
                          "US" => "United States",
                          "AF" => "Afghanistan",
                          "AL" => "Albania",
                          "DZ" => "Algeria",
                          "AS" => "American Samoa",
                          "AD" => "Andorra",
                          "AO" => "Angola",
                          "AI" => "Anguilla",
                          "AQ" => "Antarctica",
                          "AG" => "Antigua And Barbuda",
                          "AR" => "Argentina",
                          "AM" => "Armenia",
                          "AW" => "Aruba",
                          "AU" => "Australia",
                          "AT" => "Austria",
                          "AZ" => "Azerbaijan",
                          "BS" => "Bahamas",
                          "BH" => "Bahrain",
                          "BD" => "Bangladesh",
                          "BB" => "Barbados",
                          "BY" => "Belarus",
                          "BE" => "Belgium",
                          "BZ" => "Belize",
                          "BJ" => "Benin",
                          "BM" => "Bermuda",
                          "BT" => "Bhutan",
                          "BO" => "Bolivia",
                          "BA" => "Bosnia And Herzegowina",
                          "BW" => "Botswana",
                          "BV" => "Bouvet Island",
                          "BR" => "Brazil",
                          "IO" => "British Indian Ocean Territory",
                          "BN" => "Brunei Darussalam",
                          "BG" => "Bulgaria",
                          "BF" => "Burkina Faso",
                          "BI" => "Burundi",
                          "KH" => "Cambodia",
                          "CM" => "Cameroon",
                          "CA" => "Canada",
                          "CV" => "Cape Verde",
                          "KY" => "Cayman Islands",
                          "CF" => "Central African Republic",
                          "TD" => "Chad",
                          "CL" => "Chile",
                          "CN" => "China",
                          "CX" => "Christmas Island",
                          "CC" => "Cocos (Keeling) Islands",
                          "CO" => "Colombia",
                          "KM" => "Comoros",
                          "CG" => "Congo",
                          "CD" => "Congo, The Democratic Republic Of The",
                          "CK" => "Cook Islands",
                          "CR" => "Costa Rica",
                          "CI" => "Cote D'Ivoire",
                          "HR" => "Croatia (Local Name: Hrvatska)",
                          "CU" => "Cuba",
                          "CY" => "Cyprus",
                          "CZ" => "Czech Republic",
                          "DK" => "Denmark",
                          "DJ" => "Djibouti",
                          "DM" => "Dominica",
                          "DO" => "Dominican Republic",
                          "TP" => "East Timor",
                          "EC" => "Ecuador",
                          "EG" => "Egypt",
                          "SV" => "El Salvador",
                          "GQ" => "Equatorial Guinea",
                          "ER" => "Eritrea",
                          "EE" => "Estonia",
                          "ET" => "Ethiopia",
                          "FK" => "Falkland Islands (Malvinas)",
                          "FO" => "Faroe Islands",
                          "FJ" => "Fiji",
                          "FI" => "Finland",
                          "FR" => "France",
                          "FX" => "France, Metropolitan",
                          "GF" => "French Guiana",
                          "PF" => "French Polynesia",
                          "TF" => "French Southern Territories",
                          "GA" => "Gabon",
                          "GM" => "Gambia",
                          "GE" => "Georgia",
                          "DE" => "Germany",
                          "GH" => "Ghana",
                          "GI" => "Gibraltar",
                          "GR" => "Greece",
                          "GL" => "Greenland",
                          "GD" => "Grenada",
                          "GP" => "Guadeloupe",
                          "GU" => "Guam",
                          "GT" => "Guatemala",
                          "GN" => "Guinea",
                          "GW" => "Guinea-Bissau",
                          "GY" => "Guyana",
                          "HT" => "Haiti",
                          "HM" => "Heard And Mc Donald Islands",
                          "VA" => "Holy See (Vatican City State)",
                          "HN" => "Honduras",
                          "HK" => "Hong Kong",
                          "HU" => "Hungary",
                          "IS" => "Iceland",
                          "IN" => "India",
                          "ID" => "Indonesia",
                          "IR" => "Iran (Islamic Republic Of)",
                          "IQ" => "Iraq",
                          "IE" => "Ireland",
                          "IL" => "Israel",
                          "IT" => "Italy",
                          "JM" => "Jamaica",
                          "JP" => "Japan",
                          "JO" => "Jordan",
                          "KZ" => "Kazakhstan",
                          "KE" => "Kenya",
                          "KI" => "Kiribati",
                          "KP" => "Korea, Democratic People's Republic Of",
                          "KR" => "Korea, Republic Of",
                          "KW" => "Kuwait",
                          "KG" => "Kyrgyzstan",
                          "LA" => "Lao People's Democratic Republic",
                          "LV" => "Latvia",
                          "LB" => "Lebanon",
                          "LS" => "Lesotho",
                          "LR" => "Liberia",
                          "LY" => "Libyan Arab Jamahiriya",
                          "LI" => "Liechtenstein",
                          "LT" => "Lithuania",
                          "LU" => "Luxembourg",
                          "MO" => "Macau",
                          "MK" => "Macedonia, Former Yugoslav Republic Of",
                          "MG" => "Madagascar",
                          "MW" => "Malawi",
                          "MY" => "Malaysia",
                          "MV" => "Maldives",
                          "ML" => "Mali",
                          "MT" => "Malta",
                          "MH" => "Marshall Islands",
                          "MQ" => "Martinique",
                          "MR" => "Mauritania",
                          "MU" => "Mauritius",
                          "YT" => "Mayotte",
                          "MX" => "Mexico",
                          "FM" => "Micronesia, Federated States Of",
                          "MD" => "Moldova, Republic Of",
                          "MC" => "Monaco",
                          "MN" => "Mongolia",
                          "MS" => "Montserrat",
                          "MA" => "Morocco",
                          "MZ" => "Mozambique",
                          "MM" => "Myanmar",
                          "NA" => "Namibia",
                          "NR" => "Nauru",
                          "NP" => "Nepal",
                          "NL" => "Netherlands",
                          "AN" => "Netherlands Antilles",
                          "NC" => "New Caledonia",
                          "NZ" => "New Zealand",
                          "NI" => "Nicaragua",
                          "NE" => "Niger",
                          "NG" => "Nigeria",
                          "NU" => "Niue",
                          "NF" => "Norfolk Island",
                          "MP" => "Northern Mariana Islands",
                          "NO" => "Norway",
                          "OM" => "Oman",
                          "PK" => "Pakistan",
                          "PW" => "Palau",
                          "PA" => "Panama",
                          "PG" => "Papua New Guinea",
                          "PY" => "Paraguay",
                          "PE" => "Peru",
                          "PH" => "Philippines",
                          "PN" => "Pitcairn",
                          "PL" => "Poland",
                          "PT" => "Portugal",
                          "PR" => "Puerto Rico",
                          "QA" => "Qatar",
                          "RE" => "Reunion",
                          "RO" => "Romania",
                          "RU" => "Russian Federation",
                          "RW" => "Rwanda",
                          "KN" => "Saint Kitts And Nevis",
                          "LC" => "Saint Lucia",
                          "VC" => "Saint Vincent And The Grenadines",
                          "WS" => "Samoa",
                          "SM" => "San Marino",
                          "ST" => "Sao Tome And Principe",
                          "SA" => "Saudi Arabia",
                          "SN" => "Senegal",
                          "SC" => "Seychelles",
                          "SL" => "Sierra Leone",
                          "SG" => "Singapore",
                          "SK" => "Slovakia (Slovak Republic)",
                          "SI" => "Slovenia",
                          "SB" => "Solomon Islands",
                          "SO" => "Somalia",
                          "ZA" => "South Africa",
                          "GS" => "South Georgia, South Sandwich Islands",
                          "ES" => "Spain",
                          "LK" => "Sri Lanka",
                          "SH" => "St. Helena",
                          "PM" => "St. Pierre And Miquelon",
                          "SD" => "Sudan",
                          "SR" => "Suriname",
                          "SJ" => "Svalbard And Jan Mayen Islands",
                          "SZ" => "Swaziland",
                          "SE" => "Sweden",
                          "CH" => "Switzerland",
                          "SY" => "Syrian Arab Republic",
                          "TW" => "Taiwan",
                          "TJ" => "Tajikistan",
                          "TZ" => "Tanzania, United Republic Of",
                          "TH" => "Thailand",
                          "TG" => "Togo",
                          "TK" => "Tokelau",
                          "TO" => "Tonga",
                          "TT" => "Trinidad And Tobago",
                          "TN" => "Tunisia",
                          "TR" => "Turkey",
                          "TM" => "Turkmenistan",
                          "TC" => "Turks And Caicos Islands",
                          "TV" => "Tuvalu",
                          "UG" => "Uganda",
                          "UA" => "Ukraine",
                          "AE" => "United Arab Emirates",
                          "UM" => "United States Minor Outlying Islands",
                          "UY" => "Uruguay",
                          "UZ" => "Uzbekistan",
                          "VU" => "Vanuatu",
                          "VE" => "Venezuela",
                          "VN" => "Viet Nam",
                          "VG" => "Virgin Islands (British)",
                          "VI" => "Virgin Islands (U.S.)",
                          "WF" => "Wallis And Futuna Islands",
                          "EH" => "Western Sahara",
                          "YE" => "Yemen",
                          "YU" => "Yugoslavia",
                          "ZM" => "Zambia",
                          "ZW" => "Zimbabwe");
    return $arrCountries;
  }

  /**
  * hold the status array
  * @return array
  */
  function CreateStatusArray(){
    return array(1=>'Unsubscribed',
                 2=>'Unconfirmed',
                 3=>'Active',
                 4=>'Bounced',
                 5=>'Junk');
  }

  /**
  * hold the data type array
  * @return array
  */
  function CreateDataTypeArray(){
    return array(1=>'Number',
                 2=>'Date',
                 3=>'Boolean',
                 4=>'String');
  }

   /**
   * Returns an encrypted & utf8-encoded
   */
    function encrypt($strSafeWord) {
        if(trim($strSafeWord) == '')
            return '';
        $strSecureKey = hash('sha256',SALT,TRUE);
        $strIV = mcrypt_create_iv(32);
        return base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $strSecureKey, $strSafeWord, MCRYPT_MODE_ECB, $strIV ));
    }

    /**
     * Returns decrypted original string
     */
    function decrypt($strSafeWord) {
        if(trim($strSafeWord) == '')
            return '';
        $strSecureKey = hash('sha256',SALT,TRUE);
        $strIV = mcrypt_create_iv(32);
        return trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $strSecureKey, base64_decode($strSafeWord), MCRYPT_MODE_ECB, $strIV ));
    }


  /**
  * encode a PHP array into json
  * @param array $arrValues values to be encoded
  * @param $objEncodeType
  * -JSON_HEX_QUOT
  * -JSON_HEX_TAG
  * -JSON_HEX_AMP
  * -JSON_HEX_APOS
  * -JSON_NUMERIC_CHECK
  * -JSON_PRETTY_PRINT
  * -JSON_UNESCAPED_SLASHES
  * -JSON_FORCE_OBJECT
  * -JSON_PRESERVE_ZERO_FRACTION
  * -JSON_UNESCAPED_UNICODE
  * -JSON_PARTIAL_OUTPUT_ON_ERROR
  * @return string
  */
  public static function JSONEncode($arrValues,$objEncodeType = JSON_FORCE_OBJECT){//JSON_FORCE_OBJECT
     //try to encode it now
     if($strJsonData = json_encode($arrValues,$objEncodeType))
        return $strJsonData;
     switch (json_last_error()) {
        case JSON_ERROR_NONE:
            $strError = ' - No errors';
        break;
        case JSON_ERROR_DEPTH:
            $strError = ' - Maximum stack depth exceeded';
        break;
        case JSON_ERROR_STATE_MISMATCH:
            $strError = ' - Underflow or the modes mismatch';
        break;
        case JSON_ERROR_CTRL_CHAR:
            $strError = ' - Unexpected control character found';
        break;
        case JSON_ERROR_SYNTAX:
            $strError = ' - Syntax error, malformed JSON';
        break;
        case JSON_ERROR_UTF8:
            $strError =' - Malformed UTF-8 characters, possibly incorrectly encoded';
        break;
        default:
            $strError = ' - Unknown error';
        break;
    }
     Debug::Debug_er('JSONENCODE failed ['.$strError.'] LINE ['.__LINE__.'] ',1);
  }

  /**
  * decode a json array string into a php array
  * @param string $strValues values to be decoded
  * @param bool $boolAssociatve return an associative array or numerically index array
  * @return array
  */
  public static function JSONDecode($strValues, $boolAssociatve=TRUE){
     return json_decode($strValues, $boolAssociatve);
  }

  /**
  * given a status code, return the human readable description
  * @param $intCode
  * @return string ( description )
  */
  function GetHTTPResponse($intCode){
    $arrDescription = array();
    if ($intCode !== NULL) {
      switch ($intCode) {
          case 100: $arrDescription[0] = 'Continue';
            $arrDescription[1] = '#E6E312';
            break;
          case 101: $arrDescription[0] = 'Switching Protocols';
            $arrDescription[1] = '#E6E312';
            break;
          case 200: $arrDescription[0] = 'OK';
            $arrDescription[1] = '#089131';
            break;
          case 201: $arrDescription[0] = 'Created';
            $arrDescription[1] = '#E6E312';
            break;
          case 202: $arrDescription[0] = 'Accepted';
            $arrDescription[1] = '#089131';
            break;
          case 203: $arrDescription[0] = 'Non-Authoritative Information';
            $arrDescription[1] = '';
            break;
          case 204: $arrDescription[0] = 'No Content';
            $arrDescription[1] = '#CC8F0B';
            break;
          case 205: $arrDescription[0] = 'Reset Content';
            $arrDescription[1] = '#CC0000';
            break;
          case 206: $arrDescription[0] = 'Partial Content';
            $arrDescription[1] = '#CC0000';
            break;
          case 300: $arrDescription[0] = 'Multiple Choices';
            $arrDescription[1] = '#CC8F0B';
            break;
          case 301: $arrDescription[0] = 'Moved Permanently';
            $arrDescription[1] = '#CC8F0B';
            break;
          case 302: $arrDescription[0] = 'Moved Temporarily';
            $arrDescription[1] = '#CC8F0B';
            break;
          case 303: $arrDescription[0] = 'See Other';
            $arrDescription[1] = '#CC8F0B';
            break;
          case 304: $arrDescription[0] = 'Not Modified';
            $arrDescription[1] = '#CC8F0B';
            break;
          case 305: $arrDescription[0] = 'Use Proxy';
            $arrDescription[1] = '#CC8F0B';
            break;
          case 400: $arrDescription[0] = 'Bad Request';
            $arrDescription[1] = '#CC0000';
            break;
          case 401: $arrDescription[0] = 'Unauthorized';
            $arrDescription[1] = '#CC0000';
            break;
          case 402: $arrDescription[0] = 'Payment Required';
            $arrDescription[1] = '#CC0000';
            break;
          case 403: $arrDescription[0] = 'Forbidden';
            $arrDescription[1] = '#CC0000';
            break;
          case 404: $arrDescription[0] = 'Not Found';
            $arrDescription[1] = '#CC0000';
            break;
          case 405: $arrDescription[0] = 'Method Not Allowed';
            $arrDescription[1] = '#CC0000';
            break;
          case 406: $arrDescription[0] = 'Not Acceptable';
            $arrDescription[1] = '#CC0000';
            break;
          case 407: $arrDescription[0] = 'Proxy Authentication Required';
            $arrDescription[1] = '#CC0000';
            break;
          case 408: $arrDescription[0] = 'Request Time-out';
            $arrDescription[1] = '#CC0000';
            break;
          case 409: $arrDescription[0] = 'Conflict';
            $arrDescription[1] = '#CC0000';
            break;
          case 410: $arrDescription[0] = 'Gone';
            $arrDescription[1] = '#CC0000';
            break;
          case 411: $arrDescription[0] = 'Length Required';
            $arrDescription[1] = '#CC0000';
            break;
          case 412: $arrDescription[0] = 'Precondition Failed';
            $arrDescription[1] = '#CC0000';
            break;
          case 413: $arrDescription[0] = 'Request Entity Too Large';
            $arrDescription[1] = '#CC0000';
            break;
          case 414: $arrDescription[0] = 'Request-URI Too Large';
            $arrDescription[1] = '#CC0000';
            break;
          case 415: $arrDescription[0] = 'Unsupported Media Type';
            $arrDescription[1] = '#CC0000';
            break;
          case 500: $arrDescription[0] = 'Internal Server Error';
            $arrDescription[1] = '#CC0000';
            break;
          case 501: $arrDescription[0] = 'Not Implemented';
            $arrDescription[1] = '#CC0000';
            break;
          case 502: $arrDescription[0] = 'Bad Gateway';
            $arrDescription[1] = '#CC0000';
            break;
          case 503: $arrDescription[0] = 'Service Unavailable';
            $arrDescription[1] = '#CC0000';
            break;
          case 504: $arrDescription[0] = 'Gateway Time-out';
            $arrDescription[1] = '#CC0000';
            break;
          case 505: $arrDescription[0] = 'HTTP Version not supported';
            $arrDescription[1] = '#CC0000';
            break;
          default:
            $arrDescription[0] = 'Unknown http status code "' . htmlentities($intCode) . '"';
            $arrDescription[1] = '#CC0000';
          break;
      }
    }
    $arrDescription[2] = $intCode;
    return $arrDescription;
  }

  /**
  * given  URL and filename get the header HTTP code
  * @param $strURL
  * @return int ( response code ) || bool
  */
  function GetURLHeaderHTTP($strURL,$boolDescribe=FALSE){
    $strHeaders = get_headers($strURL);
    $strResult =  substr($strHeaders[0], 9, 3);
    if($boolDescribe)
        return $this->GetHTTPResponse($strResult);
    //give back our boolean truth
    if((int)$strResult !== 200){
      return FALSE;
    }
    return $strResult;
  }

  /**
  * given a url, send a CURL request
  * @param $strURL
  * @param $arrData
  * @return array ( $varResponse, $arrHeaders)
  */
  function MakeQuickCURL($strURL,$arrData,$boolGetHeaders=FALSE){
    $arrResponse = array();
    $objcURL = curl_init();
    //make our payload
    $strPayload = $this->JSONEncode($arrData);
    curl_setopt($objcURL, CURLOPT_URL, $strURL);
    curl_setopt($objcURL, CURLOPT_TIMEOUT, 30);
    curl_setopt($objcURL, CURLOPT_RETURNTRANSFER,1);
    curl_setopt( $objcURL, CURLOPT_POSTFIELDS, $strPayload );//array('payload'=>$strPayload)
    curl_setopt( $objcURL, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
    $arrResponse['result'] = curl_exec ($objcURL);
    if($boolGetHeaders)
        $arrResponse['headers'] = curl_getinfo($objcURL);
    curl_close ($objcURL);
    return $arrResponse;
  }
}//end class
?>
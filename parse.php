<?php

/*
 Copyright (c) 2014 Mathijs Vreeman
 
 Permission is hereby granted, free of charge, to any person
 obtaining a copy of this software and associated documentation
 files (the "Software"), to deal in the Software without
 restriction, including without limitation the rights to use,
 copy, modify, merge, publish, distribute, sublicense, and/or sell
 copies of the Software, and to permit persons to whom the
 Software is furnished to do so, subject to the following
 conditions:
 
 The above copyright notice and this permission notice shall be
 included in all copies or substantial portions of the Software.
 
 THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
 EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES
 OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
 NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT
 HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY,
 WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
 FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR
 OTHER DEALINGS IN THE SOFTWARE.
 */
 
 
//////////////// DEFINE PARSE.COM KEYS HERE //////////////////
//                                                          //
	  $appId   = '';  
	  $restKey = '';  
//                                                          //
//////////////////////////////////////////////////////////////


function parseUpload($fileURL) {
	if (!empty($fileURL)) {
		$fileName = basename($fileURL);
		$url = 'https://api.parse.com/1/files/' . $fileName;
		$response = parseRequest($url, 'POST', file_get_contents($fileURL), getMimeType($fileName));
		$return = $response;
	}
	else {
		$return = jsonError('parameter(s) missing');
	}
	return $return;
}

function parseFetch($parseClassName, $parametersArray) {
	if (!empty($parseClassName)) {
		$url = 'https://api.parse.com/1/classes/' . $parseClassName;
		if (isset($parametersArray)) { $url .= '?' . http_build_query(array('where' => $parametersArray)); }
		$return = parseRequest($url, 'GET', null, 'application/json');
	}
	else {
		$return = jsonError('parameter(s) missing');
	}
	return $return;
}

function parseCreate($parseClassName, $parameters) {
	if (!empty($parseClassName) && !empty($parameters)) {
		$url = 'https://api.parse.com/1/classes/' . $parseClassName;
		$return = parseRequest($url, 'POST', json_encode($parameters), 'application/json');
	}	
	else {
		$return = jsonError('parameter(s) missing');
	}
	return $return;
}

function parseDelete($parseClassName, $parseObjectID) {
	if (!empty($parseClassName) && !empty($parseObjectID)) {
		$url = 'https://api.parse.com/1/classes/' . $parseClassName . '/' . $parseObjectID;
		$return = parseRequest($url, 'DELETE', null, null);
	}
	else {
		$return = jsonError('parameter(s) missing');
	}
	return $return;
} 

function parseUpdate($parseClassName, $parseObjectID, $parameters) {
	if (!empty($parseClassName) && !empty($parseObjectID) && !empty($parameters)) {
		$url = 'https://api.parse.com/1/classes/' . $parseClassName . '/' . $parseObjectID;
		$return = parseRequest($url, 'PUT', json_encode($parameters), 'application/json');
	}
	else {
		$return = jsonError('parameter(s) missing');
	}
	return $return;
} 

function parseRequest($parseURL, $requestType, $data, $contentType) {
	global $appId, $restKey;

	$curl = curl_init();
	curl_setopt($curl,CURLOPT_URL, $parseURL);
	curl_setopt($curl,CURLOPT_PORT, 443);
	curl_setopt($curl,CURLOPT_CUSTOMREQUEST, $requestType);
	curl_setopt($curl,CURLOPT_RETURNTRANSFER, true);
    	curl_setopt($curl,CURLOPT_POSTFIELDS, $data);
	curl_setopt($curl,CURLOPT_HTTPHEADER, 
    	array(	'X-Parse-Application-Id: ' . $appId,
        		'X-Parse-REST-API-Key: ' . $restKey,
        		'Content-Type: ' . ' ' . $contentType));
	$response = curl_exec($curl);

	$httpStatus = curl_getinfo($curl, CURLINFO_HTTP_CODE);
	if ($httpStatus > 299) {
		$response = jsonError('http status error ' . $httpStatus);
	}

	curl_close($curl);
	return $response;
}

function jsonError($object) {
	$array = array('error' => $object);
	return json_encode($array);
}

function getMimeType($fileName) {
	$mimeTypes = array(
        "323"       => "text/h323",
        "acx"       => "application/internet-property-stream",
        "ai"        => "application/postscript",
        "aif"       => "audio/x-aiff",
        "aifc"      => "audio/x-aiff",
        "aiff"      => "audio/x-aiff",
        "asf"       => "video/x-ms-asf",
        "asr"       => "video/x-ms-asf",
        "asx"       => "video/x-ms-asf",
        "au"        => "audio/basic",
        "avi"       => "video/x-msvideo",
        "axs"       => "application/olescript",
        "bas"       => "text/plain",
        "bcpio"     => "application/x-bcpio",
        "bin"       => "application/octet-stream",
        "bmp"       => "image/bmp",
        "c"         => "text/plain",
        "cat"       => "application/vnd.ms-pkiseccat",
        "cdf"       => "application/x-cdf",
        "cer"       => "application/x-x509-ca-cert",
        "class"     => "application/octet-stream",
        "clp"       => "application/x-msclip",
        "cmx"       => "image/x-cmx",
        "cod"       => "image/cis-cod",
        "cpio"      => "application/x-cpio",
        "crd"       => "application/x-mscardfile",
        "crl"       => "application/pkix-crl",
        "crt"       => "application/x-x509-ca-cert",
        "csh"       => "application/x-csh",
        "css"       => "text/css",
        "dcr"       => "application/x-director",
        "der"       => "application/x-x509-ca-cert",
        "dir"       => "application/x-director",
        "dll"       => "application/x-msdownload",
        "dms"       => "application/octet-stream",
        "doc"       => "application/msword",
        "dot"       => "application/msword",
        "dvi"       => "application/x-dvi",
        "dxr"       => "application/x-director",
        "eps"       => "application/postscript",
        "etx"       => "text/x-setext",
        "evy"       => "application/envoy",
        "exe"       => "application/octet-stream",
        "fif"       => "application/fractals",
        "flr"       => "x-world/x-vrml",
        "gif"       => "image/gif",
        "gtar"      => "application/x-gtar",
        "gz"        => "application/x-gzip",
        "h"         => "text/plain",
        "hdf"       => "application/x-hdf",
        "hlp"       => "application/winhlp",
        "hqx"       => "application/mac-binhex40",
        "hta"       => "application/hta",
        "htc"       => "text/x-component",
        "htm"       => "text/html",
        "html"      => "text/html",
        "htt"       => "text/webviewhtml",
        "ico"       => "image/x-icon",
        "ief"       => "image/ief",
        "iii"       => "application/x-iphone",
        "ins"       => "application/x-internet-signup",
        "isp"       => "application/x-internet-signup",
        "jfif"      => "image/pipeg",
        "jpe"       => "image/jpeg",
        "jpeg"      => "image/jpeg",
        "jpg"       => "image/jpeg",
        "js"        => "application/x-javascript",
        "latex"     => "application/x-latex",
        "lha"       => "application/octet-stream",
        "lsf"       => "video/x-la-asf",
        "lsx"       => "video/x-la-asf",
        "lzh"       => "application/octet-stream",
        "m13"       => "application/x-msmediaview",
        "m14"       => "application/x-msmediaview",
        "m3u"       => "audio/x-mpegurl",
        "man"       => "application/x-troff-man",
        "mdb"       => "application/x-msaccess",
        "me"        => "application/x-troff-me",
        "mht"       => "message/rfc822",
        "mhtml"     => "message/rfc822",
        "mid"       => "audio/mid",
        "mny"       => "application/x-msmoney",
        "mov"       => "video/quicktime",
        "movie"     => "video/x-sgi-movie",
        "mp2"       => "video/mpeg",
        "mp3"       => "audio/mpeg",
        "mpa"       => "video/mpeg",
        "mpe"       => "video/mpeg",
        "mpeg"      => "video/mpeg",
        "mpg"       => "video/mpeg",
        "mpp"       => "application/vnd.ms-project",
        "mpv2"      => "video/mpeg",
        "ms"        => "application/x-troff-ms",
        "mvb"       => "application/x-msmediaview",
        "nws"       => "message/rfc822",
        "oda"       => "application/oda",
        "p10"       => "application/pkcs10",
        "p12"       => "application/x-pkcs12",
        "p7b"       => "application/x-pkcs7-certificates",
        "p7c"       => "application/x-pkcs7-mime",
        "p7m"       => "application/x-pkcs7-mime",
        "p7r"       => "application/x-pkcs7-certreqresp",
        "p7s"       => "application/x-pkcs7-signature",
        "pbm"       => "image/x-portable-bitmap",
        "pdf"       => "application/pdf",
        "pfx"       => "application/x-pkcs12",
        "pgm"       => "image/x-portable-graymap",
        "pko"       => "application/ynd.ms-pkipko",
        "pma"       => "application/x-perfmon",
        "pmc"       => "application/x-perfmon",
        "pml"       => "application/x-perfmon",
        "pmr"       => "application/x-perfmon",
        "pmw"       => "application/x-perfmon",
        "png"       => "image/png",
        "pnm"       => "image/x-portable-anymap",
        "pot"       => "application/vnd.ms-powerpoint",
        "ppm"       => "image/x-portable-pixmap",
        "pps"       => "application/vnd.ms-powerpoint",
        "ppt"       => "application/vnd.ms-powerpoint",
        "prf"       => "application/pics-rules",
        "ps"        => "application/postscript",
        "pub"       => "application/x-mspublisher",
        "qt"        => "video/quicktime",
        "ra"        => "audio/x-pn-realaudio",
        "ram"       => "audio/x-pn-realaudio",
        "ras"       => "image/x-cmu-raster",
        "rgb"       => "image/x-rgb",
        "rmi"       => "audio/mid",
        "roff"      => "application/x-troff",
        "rtf"       => "application/rtf",
        "rtx"       => "text/richtext",
        "scd"       => "application/x-msschedule",
        "sct"       => "text/scriptlet",
        "setpay"    => "application/set-payment-initiation",
        "setreg"    => "application/set-registration-initiation",
        "sh"        => "application/x-sh",
        "shar"      => "application/x-shar",
        "sit"       => "application/x-stuffit",
        "snd"       => "audio/basic",
        "spc"       => "application/x-pkcs7-certificates",
        "spl"       => "application/futuresplash",
        "src"       => "application/x-wais-source",
        "sst"       => "application/vnd.ms-pkicertstore",
        "stl"       => "application/vnd.ms-pkistl",
        "stm"       => "text/html",
        "svg"       => "image/svg+xml",
        "sv4cpio"   => "application/x-sv4cpio",
        "sv4crc"    => "application/x-sv4crc",
        "t"         => "application/x-troff",
        "tar"       => "application/x-tar",
        "tcl"       => "application/x-tcl",
        "tex"       => "application/x-tex",
        "texi"      => "application/x-texinfo",
        "texinfo"   => "application/x-texinfo",
        "tgz"       => "application/x-compressed",
        "tif"       => "image/tiff",
        "tiff"      => "image/tiff",
        "tr"        => "application/x-troff",
        "trm"       => "application/x-msterminal",
        "tsv"       => "text/tab-separated-values",
        "txt"       => "text/plain",
        "uls"       => "text/iuls",
        "ustar"     => "application/x-ustar",
        "vcf"       => "text/x-vcard",
        "vrml"      => "x-world/x-vrml",
        "wav"       => "audio/x-wav",
        "wcm"       => "application/vnd.ms-works",
        "wdb"       => "application/vnd.ms-works",
        "wks"       => "application/vnd.ms-works",
        "wmf"       => "application/x-msmetafile",
        "wps"       => "application/vnd.ms-works",
        "wri"       => "application/x-mswrite",
        "wrl"       => "x-world/x-vrml",
        "wrz"       => "x-world/x-vrml",
        "xaf"       => "x-world/x-vrml",
        "xbm"       => "image/x-xbitmap",
        "xla"       => "application/vnd.ms-excel",
        "xlc"       => "application/vnd.ms-excel",
        "xlm"       => "application/vnd.ms-excel",
        "xls"       => "application/vnd.ms-excel",
        "xlsx"      => "vnd.ms-excel",
        "xlt"       => "application/vnd.ms-excel",
        "xlw"       => "application/vnd.ms-excel",
        "xof"       => "x-world/x-vrml",
        "xpm"       => "image/x-xpixmap",
        "xwd"       => "image/x-xwindowdump",
        "z"         => "application/x-compress",
        "zip"       => "application/zip"
	);
   	$explode = explode('.', $fileName);
    	$extension = end($explode);
	return $mimeTypes[$extension];
}
?>

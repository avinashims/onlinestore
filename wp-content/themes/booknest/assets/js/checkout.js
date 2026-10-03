/**
 * Auto-start ebook download on order confirmation.
 */
( function () {
	'use strict';

	if ( typeof booknestCheckout === 'undefined' || ! booknestCheckout.downloadUrl ) {
		return;
	}

	function triggerDownload() {
		var url = booknestCheckout.downloadUrl;
		var link = document.createElement( 'a' );
		link.href = url;
		link.setAttribute( 'download', '' );
		link.style.display = 'none';
		document.body.appendChild( link );
		link.click();
		document.body.removeChild( link );
	}

	if ( document.readyState === 'loading' ) {
		document.addEventListener( 'DOMContentLoaded', triggerDownload );
	} else {
		triggerDownload();
	}
} )();

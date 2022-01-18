function refreshDataFromEndpoint() {
	var xmlhttp = new XMLHttpRequest();

	xmlhttp.onreadystatechange = function() {
		if ( xmlhttp.readyState == 4 ) {
			if ( xmlhttp.status !== 200 ) alert( 'There was an error ' + xmlhttp.status );
            document.location.reload();
        }
	};

	xmlhttp.open( "GET", ccc_ajax.url_endpoint + '?action=ccc_refresh_data&ccc_nonce=' + ccc_ajax.nonce, true );
	xmlhttp.send( );
}

document.addEventListener('DOMContentLoaded', function() {
    var btn_refresh = document.querySelector('#wp-crypto-currency-converter button.btn-refresh');
    btn_refresh.addEventListener('click', function() {
        refreshDataFromEndpoint();
    });
});
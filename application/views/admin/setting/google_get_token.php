<?

$client_id = "";
$client_secret = "";
$refresh_token = ""; 
$credentials = "";

	foreach ($google_setting as $row) {
		$client_id = $row->google_client_id;
		$client_secret = $row->google_client_secret;
		$refresh_token = $row->google_refresh_token;
		$credentials = $row->google_credentials;
	}
?>
<script>
const refreshToken = async () => {
	const item = {
		client_id:"<?=$client_id?>",
		client_secret:"<?=$client_secret?>",
		grant_type:"refresh_token",
		refresh_token:<?=$refresh_token;?>
	};

	console.log(formData);
	const response = await fetch('https://oauth2.googleapis.com/token', {
		method: 'POST',
		body: `client_id=${item.client_id}&client_secret=${item.client_secret}&grant_type=${item.grant_type}&refresh_token=${item.refresh_token}`, // string or object
		headers: {
			'Content-Type': 'application/x-www-form-urlencoded'
		},
	});
	const myJson = await response.json(); //extract JSON from the http response
	console.log(myJson);
	// return myJson;
// do something with myJson
}
</script>
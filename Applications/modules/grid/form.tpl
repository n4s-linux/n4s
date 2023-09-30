  <script>
  $( function() {
    $( "#dialog" ).dialog();
  } );
  </script>
<style>
#dialog p {
	font-size: 12px;
}
#dialog input[type=text] {
	float: left;
	clear: both;
	margin-bottom: 15px;
	width: 250px;
}
#dialog label {
	float: left;
	clear: both;
	font-size: 12px;
	font-weight: 600;
}
#dialog input[type=submit] {
	float: right;
	margin-right: 12px;
}


</style> 
<div id="dialog" title="Basic dialog">
	<p>This field is ment for a detailed description.</p>
  	<div class="form_field">
		<label for=name>Navn:</label>
		<input type=text name=name id=name>
	</div>
  	<div class="form_field">
		<label for=name>Adresse:</label>
		<input type=text name=name id=name>
	</div>
  	<div class="form_field">
		<label for=name>Postnr &amp; by:</label>
		<input type=text name=name id=name>
	</div>
  	<div class="form_field">
		<label for=name>E-Mail:</label>
		<input type=text name=name id=name>
	</div>
  	<div class="form_field">
		<label for=name>Telefon:</label>
		<input type=text name=name id=name>
	</div>
  	<div class="form_field">
		<input type=submit value="Opret bruger">
	</div>





</div>

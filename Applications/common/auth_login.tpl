<main role="main" class="inner cover">
<style>
.lw-form-icon {
	width: 18px;
}
</style>

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">



<style>
.login {
    margin: auto;
    width: 50%;
}
.loginform label {
    width: 120px; 
    float: left;
    padding-top: 7px;
}
.loginform input {
    width: 200px; 
    float: left;
    border-radius: .25rem;
    border-top-left-radius: 0;
    border-bottom-left-radius: 0;
    border-left: none;
}
.loginform i {
    float: left;
    padding: 9px;
    width: 32px;
    border: 1px solid black;
     border-radius: .25rem;
    border-top-right-radius: 0;
    border-bottom-right-radius: 0;
        background-color: #e9ecef;
            border: 1px solid #ced4da;
}
.loginform button {
    float: right;
    margin-right: 100px;
    margin-top: 20px;
}
.spacer {
    clear: both;
    margin: 16px;
}
</style>
<div class=login>
<form class=loginform>
<input type=hidden name=posted value=true>

    <label for="text" class="col-4 col-form-label">Regnskab</label> 
    <i class="fa fa-book lw-form-icon"></i>
    <input id="text" name="regnskab" value="{$input.regnskab}" type="text" class="form-control">
    <div class="spacer"></div>

    <label for="text2" class="col-4 col-form-label">Brugernavn</label> 
    <i class="fa fa-user lw-form-icon"></i>
    <input id="text2" name="username" value="{$input.username}" type="text" class="form-control">
    <div class="spacer"></div>
    
    <label for="text1" class="col-4 col-form-label">Adgangskode</label> 
    <i class="fa fa-lock lw-form-icon"></i>
    <input id="text1" name="password" type="password" class="form-control">
    <div class="error"></div> 
    <div class="spacer"></div>
  
    <p class="lead">
      <button class="btn btn-lg btn-secondary">Log ind</button>
    </p>
</form>
</div>
  </main>

<script>
{if $errorMessage}
$(function() {
	$(".error").css("color","red");
	$(".error").html("{$errorMessage}");
	$("input[name=regnskab]").focus();	
});
{/if}
</script>


<?php
/*
* File: register.php
* Purpose: Registration form for new buyer/seller accounts
* Dependencies: header.php, footer.php
* Flow: Display form -> Process in process_registration.php -> Create account
*/


include_once("header.php")?>

<div class="container">
<h2 class="my-3">Register new account</h2>

<!-- Registration form that submits to process_registration.php -->
<form method="POST" action="process_registration.php">

<!-- Account type selection (buyer/seller) with required field validation -->
  <div class="form-group row">
    <label for="accountType" class="col-sm-2 col-form-label text-right">Registering as a:</label>
	<div class="col-sm-10">
	  <div class="form-check form-check-inline">
        <input class="form-check-input" type="radio" name="accountType" id="accountBuyer" value="buyer" checked>
        <label class="form-check-label" for="accountBuyer">Buyer</label>
      </div>
      <div class="form-check form-check-inline">
        <input class="form-check-input" type="radio" name="accountType" id="accountSeller" value="seller">
        <label class="form-check-label" for="accountSeller">Seller</label>
      </div>
      <small id="accountTypeHelp" class="form-text-inline text-muted"><span class="text-danger">* Required.</span></small>
	</div>
  </div>
 <!-- Email input field with validation -->
  <div class="form-group row">
    <label for="email" class="col-sm-2 col-form-label text-right">Email</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" id="email" name="email" placeholder="Email">
      <small id="emailHelp" class="form-text text-muted"><span class="text-danger">* Required.</span></small>
    </div>
  </div>
 <!-- Phone number input field with validation -->
  <div class="form-group row">
    <label for="phoneNumber" class="col-sm-2 col-form-label text-right">Phone Number</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" id="phoneNumber" name="phoneNumber" placeholder="Phone Number">
      <small id="phoneNumberHelp" class="form-text text-muted"><span class="text-danger">* Required.</span></small>
    </div>
  </div>
 <!-- Password input field with validation -->
  <div class="form-group row">
    <label for="password" class="col-sm-2 col-form-label text-right">Password</label>
    <div class="col-sm-10">
      <input type="password" class="form-control" id="password" name="password" placeholder="Password">
      <small id="passwordHelp" class="form-text text-muted"><span class="text-danger">* Required.</span></small>
    </div>
  </div>
 <!-- Password confirmation field with validation -->
  <div class="form-group row">
    <label for="passwordConfirmation" class="col-sm-2 col-form-label text-right">Repeat password</label>
    <div class="col-sm-10">
      <input type="password" class="form-control" id="passwordConfirmation" name="passwordConfirmation" placeholder="Enter password again">
      <small id="passwordConfirmationHelp" class="form-text text-muted"><span class="text-danger">* Required.</span></small>
    </div>
  </div>
<!-- Submit button for form -->
  <div class="form-group row">
    <button type="submit" class="btn btn-primary form-control">Register</button>
  </div>
</form>
<!-- Link to login modal for existing users -->
<div class="text-center">Already have an account? <a href="" data-toggle="modal" data-target="#loginModal">Login</a>

</div>

<?php include_once("footer.php")?>
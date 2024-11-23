<?php include_once("header.php")?>

<?php
session_start(); # Ensures that only sellers can create an auction
if (!isset($_SESSION['logged_in']) || $_SESSION['account_type'] != 'seller') {
    header('Location: browse.php');
    exit();
}
?>

<div class="container">

<!-- Create auction form -->
<div style="max-width: 800px; margin: 10px auto">
  <h2 class="my-3">Create new auction</h2>
  <div class="card">
    <div class="card-body">
      <!-- Note: This form does not do any dynamic / client-side / JavaScript-based validation of data. It only performs checking after 
      the form has been submitted, and only allows users to try once. You can make this fancier using JavaScript to alert users of invalid data
      before they try to send it, but that kind of functionality should be extremely low-priority / only done after all database functions are complete. -->
      
      <form method="post" action="create_auction_result.php" enctype="multipart/form-data"> <!-- Form to give title and description of auctioned item -->
        <div class="form-group row">
          <label for="auctionTitle" class="col-sm-2 col-form-label text-right">Title of auction</label>
          <div class="col-sm-10">
            <input type="text" class="form-control" id="auctionTitle" placeholder="e.g. Black mountain bike" name="auctionTitle">
            <small id="titleHelp" class="form-text text-muted"><span class="text-danger">* Required.</span> A short description of the item you're selling, which will display in listings.</small>
          </div>
        </div>
        <div class="form-group row">
          <label for="auctionDetails" class="col-sm-2 col-form-label text-right">Details</label>
          <div class="col-sm-10">
            <textarea class="form-control" id="auctionDetails" name="auctionDetails" rows="4"></textarea>
            <small id="detailsHelp" class="form-text text-muted">Full details of the listing to help bidders decide if it's what they're looking for.</small>
          </div>
        </div>
        <!-- Item condition section added to the form -->
        <div class="form-group row">
          <label for="itemCondition" class="col-sm-2 col-form-label text-right">Condition</label>
          <div class="col-sm-10">
            <select class="form-control" id="itemCondition" name="itemCondition" required>
              <option selected>Choose...</option>
              <option value="new">New</option>
              <option value="like-new">Like New</option>
              <option value="excellent-condition">Excellent Condition</option>
              <option value="used">Used</option>
              <option value="damaged-or-broken">Damaged or Broken</option>
            </select>
            <small id="conditionHelp" class="form-text text-muted"><span class="text-danger">* Required.</span> Specify the condition of the item.</small>
          </div>
        </div>
        <!-- Category to help buyers narrow their search -->
        <div class="form-group row">
          <label for="auctionCategory" class="col-sm-2 col-form-label text-right">Category</label>
          <div class="col-sm-10">
            <select class="form-control" id="auctionCategory" name="auctionCategory">
              <option selected>Choose...</option>
              <option value="bike">Bike</option>
              <option value="accessories">Accessories</option>
              <option value="parts">Parts</option>
              <option value="apparel">Apparel</option>
            </select>
            <small id="categoryHelp" class="form-text text-muted"><span class="text-danger">* Required.</span> Select a category for this item.</small>
          </div>
        </div>
        <!-- Tag field added to the form -->
        <div class="form-group row">
          <label for="itemTags" class="col-sm-2 col-form-label text-right">Tags</label>
          <div class="col-sm-10">
            <input type="text" class="form-control" id="itemTags" name="itemTags" placeholder="e.g. bike, trail, blue">
            <small id="tagsHelp" class="form-text text-muted">Optional. Add comma-separated tags to help people find your listing.</small>
          </div>
        </div>
        <!-- Field for starting price -->
        <div class="form-group row">
          <label for="auctionStartPrice" class="col-sm-2 col-form-label text-right">Starting price</label>
          <div class="col-sm-10">
	        <div class="input-group">
              <div class="input-group-prepend">
                <span class="input-group-text">£</span>
              </div>
              <input type="number" class="form-control" id="auctionStartPrice" name="auctionStartPrice">
            </div>
            <small id="startBidHelp" class="form-text text-muted"><span class="text-danger">* Required.</span> Initial bid amount.</small>
          </div>
        </div>
        <!-- Reserve price field -->
        <div class="form-group row">
          <label for="auctionReservePrice" class="col-sm-2 col-form-label text-right">Reserve price</label>
          <div class="col-sm-10">
            <div class="input-group">
              <div class="input-group-prepend">
                <span class="input-group-text">£</span>
              </div>
              <input type="number" class="form-control" id="auctionReservePrice" name="auctionReservePrice">
            </div>
            <small id="reservePriceHelp" class="form-text text-muted">Optional. Auctions that end below this price will not go through. This value is not displayed in the auction listing.</small>
          </div>
        </div>
        <!-- End date field for when the auction ends -->
        <div class="form-group row">
          <label for="auctionEndDate" class="col-sm-2 col-form-label text-right">End date</label>
          <div class="col-sm-10">
            <input type="datetime-local" class="form-control" id="auctionEndDate" name="auctionEndDate">
            <small id="endDateHelp" class="form-text text-muted"><span class="text-danger">* Required.</span> Day for the auction to end.</small>
          </div>
        </div>
        <!-- Attach image field added to the form -->
        <div class="form-group row">
          <label for="itemImage" class="col-sm-2 col-form-label text-right">Attach Image</label>
          <div class="col-sm-10">
            <input type="file" class="form-control-file" id="itemImage" name="itemImage" accept="image/*">
            <small id="imageHelp" class="form-text text-muted">Optional. Upload an image for your item.</small>
          </div>
        </div>
        <button type="submit" class="btn btn-primary form-control">Create Auction</button>
      </form>
    </div>
  </div>
</div>
<!-- Potential additions: Change currency; Allow for multiple images to upload; Do we use the item condition in listings display; Do we use tags in listings display/search for items -->
</div>


<?php include_once("footer.php")?>
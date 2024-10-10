#######################################
# This script automatically copies the 'auction' folder in this repository to your htdocs folder so that XAMPP can host it.
# Set-Up:
# Create .env file in local directory and add a line HTDOCS_PATH=path_to_your_htdocs_folder to it
#       e.g. HTDOCS_PATH="/Applications/XAMPP/xamppfiles/htdocs" 
#
#######################################

# Load variables from .env file
if [ -f .env ]; then
  export $(cat .env | xargs)
else
  echo ".env file not found!"
  exit 1
fi

# Copy local auction folder to HTDOCS_PATH, overriding the existing one
if [ -d "$HTDOCS_PATH" ]; then
  cp -r auction "$HTDOCS_PATH"
  echo "Auction folder copied to $HTDOCS_PATH"
else
  echo "HTDOCS_PATH does not exist!"
  exit 1
fi

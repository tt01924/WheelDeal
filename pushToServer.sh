#######################################
# This script automatically copies the 'WheelDeal' folder in this repository to your htdocs folder so that XAMPP can host it.
# Set-Up:
# Create .env file in local directory and add a line HTDOCS_PATH=path_to_your_htdocs_folder to it
#       e.g. HTDOCS_PATH="/Applications/MAMP/htdocs" 
#
#######################################

# Check for flags 
REBUILD_SQL=false
while getopts "s" opt; do
  case $opt in
    s)
      REBUILD_SQL=true
      ;;
    \?)
      echo "Invalid option: -$OPTARG" >&2
      exit 1
      ;;
  esac
done

echo "**** Pushing to server... ****"

echo "Pushing WheelDeal folder to MAMP..."
# Load variables from .env file
if [ -f .env ]; then
  export $(cat .env | xargs)
else
  echo ".env file not found!"
  exit 1
fi

# Copy local WheelDeal folder to HTDOCS_PATH, overriding the existing one
if [ -d "$HTDOCS_PATH" ]; then
  cp -r WheelDeal "$HTDOCS_PATH"
  echo "WheelDeal folder copied to $HTDOCS_PATH"
else
  echo "HTDOCS_PATH does not exist!"
  exit 1
fi


if [ "$REBUILD_SQL" = true ]; then
  echo "Rebuilding SQL database from script..."
  python3 __rebuildDatabase.py
else
  echo "Skipping SQL database rebuild. (use -s to rebuild database)"
fi




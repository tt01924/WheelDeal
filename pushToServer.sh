#######################################
# This script automatically copies the 'WheelDeal' folder in this repository to your htdocs folder so that XAMPP can host it.
# Set-Up:
# Create .env file in local directory and add a line HTDOCS_PATH=path_to_your_htdocs_folder to it
#       e.g. HTDOCS_PATH="/Applications/MAMP/htdocs" 
#
#######################################

#!/bin/bash

# Load environment variables from the .env file
if [ -f .env ]; then
  source .env
else
  echo ".env file not found!"
  exit 1
fi

echo "HTDOCS_PATH is set to: $HTDOCS_PATH"
echo "**** Pushing to server... ****"

# Check if HTDOCS_PATH exists and is a directory
if [ ! -d "$HTDOCS_PATH" ]; then
    echo "HTDOCS_PATH does not exist or is not a directory!"
    exit 1
fi

# Copy WheelDeal folder to HTDOCS_PATH
cp -R WheelDeal "$HTDOCS_PATH" || {
  echo "Failed to copy WheelDeal folder to $HTDOCS_PATH"
  exit 1
}
echo "WheelDeal folder copied to $HTDOCS_PATH"

# Check for flags 
REBUILD_SQL=false
TOGGLE_DUMMY_DATA=false
while getopts "sd" opt; do
  case $opt in
    s)
      REBUILD_SQL=true
      ;;
    d)
      TOGGLE_DUMMY_DATA=true
      ;;
    \?)
      echo "Invalid option: -$OPTARG" >&2
      exit 1
      ;;
  esac
done

# Rebuild SQL database if specified
if [ "$REBUILD_SQL" = true ]; then
  echo "Rebuilding SQL database from script..."
  if [ "$TOGGLE_DUMMY_DATA" = true ]; then
    python3 __rebuildDatabase.py --toggleDummyData
  else
    python3 __rebuildDatabase.py
  fi
else
  echo "Skipping SQL database rebuild. (use -s to rebuild database)"
fi




# source .env

# echo "HTDOCS_PATH is set to: $HTDOCS_PATH"
# echo "**** Pushing to server... ****"

# # Check if HTDOCS_PATH exists and is a directory
# if [ ! -d "$HTDOCS_PATH" ]; then
#     echo "HTDOCS_PATH does not exist or is not a directory!"
#     exit 1
# fi

# # Copy WheelDeal folder to HTDOCS_PATH
# cp -R WheelDeal "$HTDOCS_PATH"


# # Check for flags 
# REBUILD_SQL=false
# TOGGLE_DUMMY_DATA=false
# while getopts "sd" opt; do
#   case $opt in
#     s)
#       REBUILD_SQL=true
#       ;;
#     d)
#       TOGGLE_DUMMY_DATA=true
#       ;;
#     \?)
#       echo "Invalid option: -$OPTARG" >&2
#       exit 1
#       ;;
#   esac
# done

# echo "HTDOCS_PATH is set to: $HTDOCS_PATH"

# echo "**** Pushing to server... ****"

# echo "Pushing WheelDeal folder to MAMP..."
# # Load variables from .env file
# if [ -f .env ]; then
#   export $(cat .env | xargs)
# else
#   echo ".env file not found!"
#   exit 1
# fi

# # Copy local WheelDeal folder to HTDOCS_PATH, overriding the existing one
# if [ -d "$HTDOCS_PATH" ]; then
#   cp -r WheelDeal "$HTDOCS_PATH"
#   echo "WheelDeal folder copied to $HTDOCS_PATH"
# else
#   echo "HTDOCS_PATH does not exist!"
#   exit 1
# fi


# if [ "$REBUILD_SQL" = true ]; then
#   echo "Rebuilding SQL database from script..."
#   if [ "$TOGGLE_DUMMY_DATA" = true ]; then
#     python3 __rebuildDatabase.py --toggleDummyData
#   else
#     python3 __rebuildDatabase.py
#   fi
# else
#   echo "Skipping SQL database rebuild. (use -s to rebuild database)"
# fi




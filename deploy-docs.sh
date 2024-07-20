#!/bin/bash

# Exit script on error
set -e

# Variables
BUILD_DIR="docs-build"
BRANCH="gh-pages"
BUILD_SUBDIR="docs"  # Adjust this if the package.json is in a different subdirectory

# Build the project
echo "Building the project..."
cd $BUILD_SUBDIR
npm install
npm run build
cd ..

# Ensure the build directory exists
if [ ! -d "$BUILD_DIR" ]; then
  echo "Build directory does not exist. Please check your build configuration."
  exit 1
fi

# Check out the gh-pages branch, or create it if it does not exist
echo "Switching to $BRANCH branch..."
git checkout $BRANCH || git checkout --orphan $BRANCH

# Remove all files in the gh-pages branch, excluding the docs directory
echo "Removing all files except for $BUILD_DIR..."
git rm -rf docs

# Copy the build files to the root of the gh-pages branch
echo "Copying build files to $BRANCH branch..."
git mv docs-build docs

# Add all files to the git index
echo "Adding files to git..."
git add .

# Commit the changes
echo "Committing changes..."
git commit -m "Deploy documentation"

# Push to the gh-pages branch
echo "Pushing to $BRANCH branch..."
git push origin $BRANCH

# Switch back to the main branch
echo "Switching back to docs branch..."
git checkout docs

echo "Deployment complete."
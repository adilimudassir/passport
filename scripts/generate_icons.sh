#!/bin/bash
set -e

# Configuration
SOURCE_ICON="resources/img/logo.png"
DIST_DIR="dist"
ICONSET_DIR="icons.iconset"
PYTHON_SCRIPT="scripts/png_to_ico.py"

# Ensure dist directory exists
mkdir -p "$DIST_DIR"
mkdir -p "$ICONSET_DIR"

echo "Generating icons from $SOURCE_ICON..."

# Create standard sizes for .icns
# 16x16, 32x32, 128x128, 256x256, 512x512, 1024x1024 (and @2x variants)
sizes=(16 32 128 256 512)

for size in "${sizes[@]}"; do
    # Standard size
    sips -s format png -z $size $size "$SOURCE_ICON" --out "$ICONSET_DIR/icon_${size}x${size}.png" > /dev/null
    
    # Retina size (@2x)
    double_size=$((size * 2))
    sips -s format png -z $double_size $double_size "$SOURCE_ICON" --out "$ICONSET_DIR/icon_${size}x${size}@2x.png" > /dev/null
done

# Need separate logic for the largest 1024x1024 since 512@2x covers it, but iconutil might expect icon_512x512@2x.png to be it.
# Actually, 1024x1024 is usually explicitly icon_512x512@2x.png.
# Standard macOS iconset structure:
# icon_16x16.png
# icon_16x16@2x.png
# icon_32x32.png
# icon_32x32@2x.png
# icon_128x128.png
# icon_128x128@2x.png
# icon_256x256.png
# icon_256x256@2x.png
# icon_512x512.png
# icon_512x512@2x.png

echo "Creating .icns file..."
iconutil -c icns "$ICONSET_DIR" -o "$DIST_DIR/icons.icns"

echo "Creating .ico file..."
# For Windows, usually 256x256 is good for high res, or a composite. 
# Our simple script takes one PNG and wraps it. We'll use the 256x256 one.
# Re-generating a specifically named 256 png for clarity
sips -s format png -z 256 256 "$SOURCE_ICON" --out "$ICONSET_DIR/win_256.png" > /dev/null
python3 "$PYTHON_SCRIPT" "$ICONSET_DIR/win_256.png" "$DIST_DIR/icons.ico"

echo "Cleaning up..."
rm -rf "$ICONSET_DIR"

echo "Done! Icons generated in $DIST_DIR/"
ls -l "$DIST_DIR/icons.icns" "$DIST_DIR/icons.ico"

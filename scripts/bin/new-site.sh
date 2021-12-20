#!/bin/bash

#1. Copy the theme
cp -r web/themes/custom/provus web/themes/custom/${THEME_NAME}

#2. Update theme filenames: 
sudo apt-get update; sudo apt-get install rename
rename "s/provus/${THEME_NAME}/" web/themes/custom/${THEME_NAME}/*.*

#3. Update theme strings: 
sed -i 's/provus/${THEME_NAME}/g' $(find web/themes/custom/${THEME_NAME} -type f)

#4. Update blocks 
sed -i "s/provus/${THEME_NAME}/g" config/default/block.*.yml

#5. Update default theme 
sed -i "s/provus/${THEME_NAME}/g" config/default/theme.settings.yml

#5. Add "${THEME_NAME}" in theme section to 
sed -i "s/provus/${THEME_NAME}/g" config/default/core.extension.yml

#6. Update responsive images 
sed -i "s/provus/${THEME_NAME}/g" config/default/responsive_image.styles.*.yml

#7. Update composer.json
sed -i "s/provus/${THEME_NAME}/g" composer.*

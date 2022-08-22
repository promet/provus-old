#!/bin/bash
# Script to quickly create sub-theme.

echo '
+------------------------------------------------------------------------+
| With this script you could quickly create a Provus 2.0 sub-theme       |
| In order to use this:                                                  |
| - provus theme (this folder) should be in the custom folder            |
+------------------------------------------------------------------------+
'
echo 'The machine name of your custom theme? [e.g. CUSTOM_PROVUS]'
read CUSTOM_PROVUS

echo 'Your theme name ? [e.g. My Custom Provus Site]'
read CUSTOM_PROVUS_NAME

if [[ ! -e ../../custom ]]; then
    mkdir ../../custom
fi
cd ../../custom
cp -r ../custom/provus_bootstrap $CUSTOM_PROVUS
cd $CUSTOM_PROVUS
for file in *provus_bootstrap.*; do mv $file ${file//provus_bootstrap/$CUSTOM_PROVUS}; done
for file in config/*/*provus_bootstrap.*; do mv $file ${file//provus_bootstrap/$CUSTOM_PROVUS}; done

# Remove create_subtheme.sh file, we do not need it in customized subtheme.
rm scripts/create_subtheme.sh

# mv {_,}$CUSTOM_PROVUS.theme
grep -Rl Provus .|xargs sed -i -e "s/provus_bootstrap/$CUSTOM_PROVUS/"
sed -i -e "s/Provus Bootstrap/$CUSTOM_PROVUS_NAME/" $CUSTOM_PROVUS.info.yml
echo "# Check the themes/custom folder for your new sub-theme."

# Remove the originals
cd ../$CUSTOM_PROVUS
rm $CUSTOM_PROVUS.info.yml'-e'
rm 'README'.txt'-e'

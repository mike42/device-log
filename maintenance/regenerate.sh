#!/bin/bash
# Save the config
echo "Saving"..
mkdir -p ../dl.save
mkdir -p ../dl.save/lib/util
mkdir -p ../dl.save/public
mkdir -p ../dl.save/public/js
mkdir -p ../dl.save/public/css
mv ../dl/site ../dl.save/
cp ../dl/*.html ../dl.save/
cp ../dl/public/bg.jpg  ../dl/public/loading.gif ../dl.save/public
cp ../dl/public/css/extra-global.css ../dl.save/public/css
cp ../dl/public/js/dl.js ../dl/public/js/jquery.backstretch.min.js ../dl.save/public/js
cp ../dl/lib/util/session.php ../dl.save/lib/util/session.php
rm -Rf ../dl

# Regenerate
echo "Generating.."
cd ../../php-mysql-model-generator
rm -Rf dl && ./generate.php dl < ../device-log/maintenance/database/device-log.sql
mv dl ../device-log/
cd ../device-log

# Replace config
echo "Replacing.."
cp -R dl.save/* dl && rm -Rf dl.save


#!/bin/bash
# Save the config
echo "Saving"..
mv ../dl/site ../
cp ../dl/*.html ../
rm -Rf ../dl

# Regenerate
echo "Generating.."
cd ../../php-mysql-model-generator
rm -Rf dl && ./generate.php dl < ../device-log/maintenance/database/device-log.sql
mv dl ../device-log/
cd ../device-log

# Replace config
echo "Replacing.."
mv dl/site dl/site.default
mv site dl/
mv *.html dl/

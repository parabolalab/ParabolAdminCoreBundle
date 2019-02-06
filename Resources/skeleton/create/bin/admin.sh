 cp -r vendor/parabol/parabol-admin-core-bundle/Resources/skeleton/admin_data/* src/$1

 find ./$1/Controller -type f -exec sed -i '' -e 's/EntitySkeleton/$2/g' {} \;
 find ./$1/Form/Type -type f -exec sed -i '' -e 's/EntitySkeleton/$2/g' {} \;
 find ./$1/Resources/views -type f -exec sed -i '' -e 's/EntitySkeleton/$2/g' {} \;
#/bin/sh

cp -r vendor/parabol/parabol-admin-core-bundle/Resources/skeleton/admin_data/* src/$1

find src/$1/Controller -type f -exec sed -i '' -e "s/AppBundle/$1/g" {} \;
find src/$1/Form/Type -type f -exec sed -i '' -e "s/AppBundle/$1/g" {} \;
find src/$1/Resources/views -type f -exec sed -i '' -e "s/AppBundle/$1/g" {} \;

find src/$1/Controller -type f -exec sed -i '' -e "s/EntitySkeleton/$2/g" {} \;
find src/$1/Form/Type -type f -exec sed -i '' -e "s/EntitySkeleton/$2/g" {} \;
find src/$1/Resources/views -type f -exec sed -i '' -e "s/EntitySkeleton/$2/g" {} \;

mv src/$1/Controller/EntitySkeleton src/$1/Controller/$2 
mv src/$1/Form/Type/EntitySkeleton src/$1/Form/Type/$2
mv src/$1/Resources/views/EntitySkeletonActions src/$1/Resources/views/$2Actions
mv src/$1/Resources/views/EntitySkeletonEdit src/$1/Resources/views/$2Edit
mv src/$1/Resources/views/EntitySkeletonList src/$1/Resources/views/$2List
mv src/$1/Resources/views/EntitySkeletonNew src/$1/Resources/views/$2New
mv src/$1/Resources/views/EntitySkeletonShow src/$1/Resources/views/$2Show

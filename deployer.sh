set -e

echo "Deploying application..."

#Enter maintenance mode
(php artisan down --message 'The app is  being (quickly) updated. Please try again in a min.') || true
	#Update codebase
	git pull origin master
#Exit maintenance mode
php artisan up

echo "Application Deployed!"
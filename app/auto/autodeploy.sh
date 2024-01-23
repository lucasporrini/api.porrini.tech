# move to api folder
cd ../../

# execute the command
git pull origin main >> logs/auto/deploy.log 2>> logs/auto/error.log

# move to current folder
cd -
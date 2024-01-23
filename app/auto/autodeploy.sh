# move to api folder
cd ../../

# add the date UTC+1 to the log file
date >> ./logs/auto/deploy.log
date >> ./logs/auto/error.log

# execute the command
git pull origin main >> ./logs/auto/deploy.log

# move to current folder
cd -
# Define the log file
log_file="/chemin/vers/votre/fichier/log.txt"

# Add the date to the log file
echo "----------------------" >> "$log_file"
date "+%Y-%m-%d %H:%M:%S" >> "$log_file"

# Memory and CPU usage
echo "Mémoire et utilisation CPU :" >> "$log_file"
free -h >> "$log_file"
top -b -n 1 | head -n 5 >> "$log_file"

# Disk space
echo "Espace disque :" >> "$log_file"
df -h >> "$log_file"

# System load
echo "Charge système :" >> "$log_file"
uptime >> "$log_file"

# Check memory usage
memory=$(free | awk '/Mem:/ {printf("%.0f"), $3/$2 * 100}')
echo "Utilisation de la mémoire : $memory%" >> "$log_file"

# Check if memory usage is greater than 20%
if [ "$memory" -gt 20 ]; then
  echo "La mémoire est supérieure à 20%." >> "$log_file"
  # Send an email to the system administrator
  message="La mémoire est supérieure à 20%. Utilisation actuelle : $memory% à $(date)"
  echo "$message" | mail -s "Alerte : Mémoire haute sur $(hostname)" 2608lucas@gmail.com
  echo "$message" >> "$log_file"
fi
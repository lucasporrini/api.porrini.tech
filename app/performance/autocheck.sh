# Memory and CPU usage
echo "Mémoire et utilisation CPU :"
free -h
top -b -n 1 | head -n 5

# Disk space
echo "Espace disque :"
df -h

# System load
echo "Charge système :"
uptime

# Check the memory usage
memory=$(free | awk '/Mem:/ {printf("%.0f"), $3/$2 * 100}')

# Check if the memory usage is greater than 20%
if [ "$memory" -gt 20 ]; then
  echo "La mémoire est supérieure à 20%."
  # Send an email to the system administrator
  mail -s "Alerte : Mémoire haute sur $(hostname) - $date" 2608lucas@gmail.com << EOF
La mémoire est supérieure à 20%. Utilisation actuelle : $memory% à $date
EOF
fi
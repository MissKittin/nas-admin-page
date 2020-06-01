#!/bin/ksh
### Shell-side system control
###	Used in functions-system.php
###		functions-system_services.php
###		functions-user_services.php
###		index.php
###		logs.php
###		storage.php
###	Provide info
###		controls

###   index:     line:   interactions:     line:
### index.php   50 168         2          362~397
### storage.php 50 160         0             -
### logs.php     418           0             -
###
### 01. disk_usage
### 02b |-disks_status_mini
### 03. block_devices - moved to disks_status
### 04. ata_channels - moved to disks_status
### 05. check_mountpoints
### 02. disks_status
### 06. ram_usage
### 07. ram_usage_bars
### 08. logged_users
### 09. vpn_user_logged
### 10. check_service
### 11. check_loaded_service
### 12. service
### 13. check_user_service
### 14. user_service
### 15. update_reminder
### 16. check_root_ro
### 17. ethtool.cmd

# Setup
XL2TPD_IP='10.0.5.1' # from /etc/xl2tpd/xl2tpd.conf -> local ip

# Colors
CGREEN='00aa00'
CRED='ff0000'
CYELLOW='cccc00'
# Spans
GREEN='<span style="color: #'"$CGREEN"';">'
RED='<span style="color: #'"$CRED"';">'

# Debug
#echo -n o >> /tmp/count.txt

case $1 in
### index.php storage.php ###
	disk_usage)
		fquery()
		{
			### function parameters: fquery_nodev=false|true

			# Edit parameters
			case $6 in
				'/')
					MOUNTPOINT=' root.usb'
					DEVICE='sda1'
				;;
				*)
					MOUNTPOINT=`echo $6 | sed -e 's\/media/\ \g'`
					DEVICE=`echo $1 | sed -e 's\/dev/\ \g'`
				;;
			esac
			BAR_PERCENT=`echo $5 | sed -e 's/%/px/g'`
			# Color bars
			BAR_COLOR=$CGREEN
			[ "`echo $5 | sed -e 's/%/ /g'`" -ge 70 ] && \
				BAR_COLOR=$CYELLOW
			[ "`echo $5 | sed -e 's/%/ /g'`" -ge 95 ] && \
				BAR_COLOR=$CRED
			# Create bar
			BAR='<div class="bar-out">
				<div class="bar" style="width: '"$BAR_PERCENT"'; background-color: #'"$BAR_COLOR"';">
				</div>
			</div>'
			# Make table row
			echo "<tr>
				<td>$MOUNTPOINT</td>
				<td>$2</td><!-- size -->
				<td>$3</td><!-- used -->
				<td>$4</td><!-- avail -->
				"; [ $fquery_nodev ] || echo "<td>$DEVICE</td>"; echo "
				<td>$BAR</td>
				<td style='text-align: right'>$5</td><!-- used -->
			</tr>"
		}

		case $2 in
			'') # normal
				# root
				df -h / | tail -n +2 | while read line; do
					fquery $line
				done
		
				# storage
				df -h | grep media | sort -k2 | while read line; do
					fquery $line
				done
			;;
			*) # custom
				[ "$3" = 'nodev' ] && fquery_nodev=true # setup fquery
				df -h | grep $2 | sort -k2 | while read line; do
					fquery $line
				done
			;;
		esac
	;;
	disks_status_mini)
		fquery()
		{
			DEVICE=$1
			[ "$5" = 'standby' ] && \
				STATE="${GREEN}$5</span>" || \
				STATE="${RED}$5</span>"
			[ "$5" = 'ssd' ] && STATE="ssd" # Added

			# Make table
			echo -n "<tr><td>/dev/$DEVICE</td>"
			echo "<td style='text-align: center;'>$STATE</td></tr>"
		}

		cd /dev
		for i in sd?; do
			case `cat /sys/block/$i/queue/rotational` in
				0)
					# Added rotational			
					fquery $i d d d ssd
				;;
				1)
					hdparm -C $i | tail -n +2 | tr -d ':' | xargs | while read line; do
						fquery $line
					done
				;;
			esac
		done
	;;
### storage.php ###
	check_mountpoints)
		for i in `ls -A /media`; do
			mountpoint -q /media/$i && \
				echo '<tr><td>'"$i"'</td><td><a href="storage.php?mountpoint='"$i"'&action=umount">Umount</a></td></tr>' || \
				echo '<tr><td>'"$i"'</td><td><a href="storage.php?mountpoint='"$i"'&action=mount">Mount</a></td></tr>'
		done
	;;
	disks_status)
		schquery()
		{
			for i in $@; do
				echo $i | grep '\[' && break
			done
		}
		hdtempquery()
		{
			[ "$1" = "false" ] && return
			[ "$4" = "C" ] && echo -n "$3 <span style='vertical-align: super;'>o</span>C" && return
			[ "$5" = "C" ] && echo -n "$4 <span style='vertical-align: super;'>o</span>C" && return
			echo -n "-"
		}
		chnquery()
		{
			ls -l /sys/block | grep $1 | tr "/" " " | awk '{print $15}'
		}
		fquery()
		{
			DEVICE=$1
			# Removed [ "$1" = '' ] && return
			[ "$5" = 'standby' ] && \
				STATE="${GREEN}$5</span>" || \
				STATE="${RED}$5</span>"
			[ "$5" = 'ssd' ] && STATE="ssd" # Added

			# Added scheduler (must be "full" parameter)
			SCHEDULER=`cat /sys/block/$i/queue/scheduler`
			
			# Added hddtemp
			HDDTEMP=`hddtemp /dev/$DEVICE 2>&1` || HDDTEMP="false"

			# Added model
			MODEL=`cat /sys/block/$i/device/model`

			# Make table
			echo -n "<tr><td>/dev/$DEVICE</td>" #device
			echo "<td style='text-align: center;'>$STATE</td>" #state
			echo "<td style='text-align: center;'>"; schquery $SCHEDULER; echo "</td>" #governor
			echo "<td style='text-align: center;'>"; hdtempquery $HDDTEMP; echo "</td>" #temperature
			echo "<td style='text-align: right;'>$MODEL</td>" #model
			echo "<td style='text-align: center;'>"; chnquery $DEVICE; echo "</td>" #channel
		}

		cd /dev
		for i in sd?; do
			case `cat /sys/block/$i/queue/rotational` in
				0)
					# Added rotational			
					fquery $i d d d ssd
				;;
				1)
					hdparm -C $i | tail -n +2 | tr -d ':' | xargs | while read line; do
						fquery $line
					done
				;;
			esac
		done
	;;
### index.php ###
	ram_usage)
		fquery()
		{
			case $1 in
				'-/+') # Buff
					echo "<tr>
						<td>Buff: </td>
						<td>$3</td><!-- used -->
						<td>$4</td><!-- total -->
						<!-- --><td></td><td></td><td></td><!-- -->
						<td>`$0 ram_usage_bars $1`</td>
					</tr>"
				;;
				'Swap:') # Swap
					[ "$2" = "0B" ] || \
					echo "<tr>
						<td>$1 </td><!-- Swap: -->
						<td>$3</td><!-- used -->
						<td>$2</td><!-- total -->
						<!-- --><td></td><td></td><td></td><!-- -->
						<td>`$0 ram_usage_bars $1`</td>
					</tr>"
				;;
				*) # Mem
					echo "<tr>
						<td>$1 </td><!-- Mem: -->
						<td>$3</td><!-- used -->
						<td>$2</td><!-- total -->
						<td>$5</td><!-- shr -->
						<td>$6</td><!-- buff -->
						<td><span style='text-decoration: underline;'>$7</span></td><!-- cchd -->
						<td>`$0 ram_usage_bars $1`</td>
					</tr>"
				;;
			esac
		}
		free -h | tail -n +2 | while read line; do
			fquery $line
		done
	;;
	ram_usage_bars)
		fquery()
		{
			### Added: Cached ram bar (BAR_CACHED)
			## $1(Mem:) $2(1031716) $3(487920) $4(543796) $5(0) $6(13084)
			# Parameters
			[ "$1" = '-/+' ] && \
				BAR_PERCENT=$((($3*100)/($4+$3))) || \
				BAR_PERCENT=$((($3*100)/$2))
			[ "$1" = '-/+' ] || \
				BAR_CACHED=$((($7*100)/$2))
			# Color bars
			BAR_COLOR=$CGREEN
			[ "$BAR_PERCENT" -ge 60 ] && \
				BAR_COLOR=$CYELLOW || \
			[ "$BAR_PERCENT" -ge 80 ] && \
				BAR_COLOR=$CRED
			# Create bar
			BAR='<div class="bar-out">
				<div class="bar" style="width: '"$BAR_PERCENT"'px; background-color: #'"$BAR_COLOR"';">
					<div class="bar" style="float: right; width: '"$BAR_CACHED"'px; background-color: #8F00FF;">
					</div>
				</div>
			</div>'

			# Print
			echo "$BAR"
		}
		free | grep -e $2 | while read line; do
			fquery $line
		done
	;;
	logged_users)
		fquery()
		{
			HOST=`echo $6 | sed -e 's/(/ /g' | sed -e 's/)/ /g'`
			[ $HOST ] || HOST="local terminal"
			### OLD METHOD: <button type="submit" name="kick_user" value="'"$1"'">Kick</button>
			echo "<tr>
				<td>$1</td><!-- user -->
				<td>$2</td><!-- term -->
				<td>$4 $3 $5</td><!-- date -->
				<td>$HOST</td><!-- ip -->
				<td>"'<button type="submit" name="kick_user" value="'"$2"'">Kick</button></td>
			</tr>'
		}
		if ! last | grep 'still logged in' > /dev/null 2>&1; then
			echo '<tr>
				<td>-</td>
				<td>-</td>
				<td>- - -</td>
				<td>-</td>
			</tr>'
		else
			who | while read line; do
				fquery $line
			done
		fi
	;;
	vpn_user_logged)
		# PPTP
		if last | grep 'still logged in' | grep "vpn" > /dev/null 2>&1; then
			fquery()
			{
				echo "<tr>
					<td>$1</td><!-- user -->
					<td>$2</td><!-- term -->
					<td>$6 $5 $7</td><!-- date -->
					<td>$3</td><!-- ip -->
				</tr>"
			}
			last | grep 'still logged in' | grep 'vpn' | while read line; do
				fquery $line
			done
		fi
		# L2DP
		ifconfig | grep -e 'ppp' -e $XL2TPD_IP | grep $XL2TPD_IP > /dev/null 2>&1 && \
			echo '<tr>
				<td>L2TP</td>
				<td>-</td>
				<td>- - -</td>
				<td>-</td>
			<tr>'
	;;
	check_service)
		case $2 in
			xl2tpd)
				case $3 in
					css-xl2tpd)
						/etc/init.d/ipsec status > /dev/null 2>&1 || \
							echo -n 'visibility: hidden;'
					;;
					css-ipsec)
						ps -A | grep xl2tpd > /dev/null 2>&1 && \
							echo -n 'visibility: hidden;'
					;;
					*)
						ps -A | grep xl2tpd > /dev/null 2>&1 && \
							echo "${GREEN}Running</span>" || \
							echo "${RED}Stopped</span>"
					;;
				esac
			;;
			bluetooth)
				ps -A | grep bluetoothd > /dev/null 2>&1 && \
					echo "${GREEN}Running</span>" || \
					echo "${RED}Stopped</span>"
			;;
			acpid-autosuspend)
				ps -A | grep acpid-autosuspe > /dev/null 2>&1 &&
					echo "${GREEN}Running</span>" || \
					echo "${RED}Stopped</span>"
			;;
			*)
				/etc/init.d/$2 status > /dev/null 2>&1 && \
					echo "${GREEN}Running</span>" || \
					echo "${RED}Stopped</span>"
			;;
		esac
		case $2 in
			samba)
				[ `ps -A | grep smbd | wc -l` -gt 1 ] && \
					echo 'used'
			;;
			vsftpd)
				[ `ps -A | grep vsftpd | wc -l` -gt 1 ] && \
					echo 'used'
			;;
		esac
	;;
	check_loaded_service)
		/etc/init.d/$2 status > /dev/null 2>&1 && \
			echo "${GREEN}Loaded</span>" || \
			echo "${RED}Stopped</span>"
	;;
### index.php interaction ###
	service)
		/etc/init.d/$2 $3 > /dev/null 2>&1
		if [ "$2" = 'ufw' ]; then
			/usr/local/sbin/pptpd-routing quiet
			/usr/local/sbin/xl2tpd-routing quiet
		fi
	;;
	check_user_service)
		case $2 in
			mocp)
				ps -A | grep mocp > /dev/null 2>&1 && \
					echo "${RED}Running</span>" || \
					echo "${GREEN}Stopped</span>"
			;;
			mpd)
				ps -A | grep mpd > /dev/null 2>&1 && \
					echo "${RED}Running</span>" || \
					echo "${GREEN}Stopped</span>"
			;;
			obexftpd)
				case $3 in
					css)
						/usr/local/bin/obexftpd-starter status && \
							echo -n 'visibility: hidden;'
					;;
					*)
						/usr/local/bin/obexftpd-starter status && \
							echo "${RED}Running</span>" || \
							echo "${GREEN}Stopped</span>"
					;;
				esac
			;;
		esac
	;;
### index.php interaction ###
	user_service)
		case $2 in
			mocp)
				./shell_user.sh "mocp -x"
			;;
			mpd)
				[ "$3" = 'start' ] && \
					./shell_user.sh "/usr/bin/mpd"
				[ "$3" = 'stop' ] && \
					./shell_user.sh "/usr/bin/mpd --kill"
			;;
			# obexftpd-starter directly called from functions-user_services.php
		esac
	;;
	update_reminder)
		[ -z "$(find -H /var/lib/apt/lists -maxdepth 0 -mtime -7)" ] && echo -n 'update'
	;;
	check_root_ro)
		mount | grep 'on / type' | grep 'ro,' > /dev/null && echo -n 'readonly'
	;;
### logs.php ###
	ethtool.cmd)
		for i in `ls /sys/class/net`; do
			/sbin/ethtool $i
			echo; echo;
		done
	;;
esac
exit 0
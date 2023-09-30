#!/bin/bash

# Code from StackOverflow user Charles Duffy:
# https://stackoverflow.com/questions/37334681/parsing-ics-file-with-bash

#    echo "Usage:"
#    echo "   $0"
#    echo "   Pipe in an ical file"
#    echo ""
#    echo "   Output structure:"
#    echo "      starttime endtime text"
#    echo "         starttime and endtime are linux epochs"

local_date() {
  local tz=${tzid[$1]}
  local dt=${content[$1]}
  if [[ $dt = *Z ]]; then
    tz=UTC
    dt=${dt%Z}
  fi
  shift

  if [[ $dt = *T* ]]; then
    dt="${dt:0:4}-${dt:4:2}-${dt:6:2}T${dt:9:2}:${dt:11:2}"
  else
    dt="${dt:0:4}-${dt:4:2}-${dt:6:2}"
  fi

  # note that this requires GNU date
  date --date="TZ=\"$tz\" $dt" "$@"
}

handle_event() {
  #if [[ "${content[LAST-MODIFIED]}" = "${content[CREATED]}" ]]; then
  #  echo "New Event Created"
  #else
  #  echo "Modified Event"
  #fi
  duration=$(( $(local_date DTEND +%s) - $(local_date DTSTART +%s) ));
  
  printf '%s\t' "$(local_date DTSTART +%Y-%m-%d)" "${content[SUMMARY]}" ; echo
}

declare -A content=( ) # define an associative array (aka map, aka hash)
declare -A tzid=( )    # another associative array for timezone info

cat ~/tmp/ical.ics|while IFS=: read -r key value; do
  value=${value%$'\r'} # remove DOS newlines
  if [[ $key = END && $value = VEVENT ]]; then
    handle_event # defining this function is up to you; see suggestion below
    content=( )
    tzid=( )
  else
    if [[ $key = *";TZID="* ]]; then
      tzid[${key%%";"*}]=${key##*";TZID="}
    fi
    content[${key%%";"*}]=$value
  fi
done

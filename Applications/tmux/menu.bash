	tmux display-menu -T "#[align=centre fg=green]Spotify" -x L -y P \
        "" \
        "-#[nodim]Track: $track_name" "" "run -b 'printf \"%s\" $quoted_track_name | pbcopy'" \
        "-#[nodim]Artist: $artist"    "" "" \
        "-#[nodim]Album: $album"      "" "" \
        "" \
        "Copy URL"         c "run -b 'printf \"%s\" $id | pbcopy'" \
        "Open Spotify"     o "run -b 'source \"$CURRENT_DIR/spotify.sh\" && open_spotify'" \
        "Play/Pause"       p "run -b 'source \"$CURRENT_DIR/spotify.sh\" && toggle_play_pause'" \
        "Previous"         b "run -b 'source \"$CURRENT_DIR/spotify.sh\" && previous_track'" \
        "Next"             n "run -b 'source \"$CURRENT_DIR/spotify.sh\" && next_track'" \
        "$repeating_label" r "run -b 'source \"$CURRENT_DIR/spotify.sh\" && toggle_repeat $is_repeat_on'" \
        "$shuffling_label" s "run -b 'source \"$CURRENT_DIR/spotify.sh\" && toggle_shuffle $is_shuffle_on'" \
        "" \
        "Close menu"       q ""
#tmux display-menu -T "#[align=centre]#{pane_index} (#{pane_id})" -x P -y P -t 1 -T "n4s ( $(basename "$tpath") ) - $LEDGER_BEGIN - $LEDGER_END - depth: $LEDGER_DEPTH" "Åbn" å lsp Ny n lsp Rapportparametre p lsp Balance b lsp Kontokort k lsp "HTML Export" x lsp Logic o lsp "Tagging ($tpath)" g lsp "CSV import" c lspDD



#tmux display-menu Parametre a Parametre

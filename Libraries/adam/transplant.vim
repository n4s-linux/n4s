function! GetVisual() range
    let reg_save = getreg('"')
    let regtype_save = getregtype('"')
    let cb_save = &clipboard
    set clipboard&
    normal! ""gvy
    let selection = getreg('"')
    call setreg('"', reg_save, regtype_save)
    let &clipboard = cb_save
    normal! gvd " Delete visual selection
    return selection
endfunction

function! Transplant() range
    " Yank the selected text into variable visual_selected
    let visual_selected = GetVisual()

    " Check if visual_selected is not empty
    if visual_selected == ""
        echo "No text selected. Please select text in visual mode and try again."
        return
    endif

    " Store the selected lines
    let g:transplant_lines = a:firstline.",".a:lastline

    " Call fzf to select a file
    let l:tempname = tempname()
    let l:cmd = '!fzf --multi > ' . l:tempname
	let l:cmd = '!cd $tpath/.tags/;ls *|grep -v .diff|grep -v .scrabble|fzf --multi > ' . l:tempname
    silent execute l:cmd
    redraw!
    let selected_file = readfile(l:tempname)
    call delete(l:tempname)

    " Check if fzf was aborted
    if len(selected_file) == 0
        echo "No file selected. Aborted."
        return
    endif

    call HandleFileSelection($tpath . "/.tags/" . selected_file[0], visual_selected)
endfunction

" HandleFileSelection function
function! HandleFileSelection(target_file, selected_text)
    if len(a:target_file) == 0
        echo "No file selected. Please try again."
        return
    endif

    " Append yanked selection to chosen file
    call writefile(split(a:selected_text, '\n'), a:target_file, 'a')
endfunction

" Key mapping in visual mode
vnoremap Y :<C-u>call Transplant()<CR>

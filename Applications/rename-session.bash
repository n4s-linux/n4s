sess=$(tmux display-message -p '#S')
echo $sess
pri=$(echo "$sess"|awk '{print $1}')
rest=$(echo "$sess"|awk '{print $3 $4 $5 $6}')
valg=$(echo -e "Up\nDown"|fzf --header "vælg hvad du vil gøre med denne sessions priortet ($pri)")
if [ "$valg" == "Up" ]; then
	pri=$(echo "$pri-100"|bc)
elif [ "$valg" == "Down" ]; then
	pri=$(echo "$pri+100"|bc)
fi
tmux rename-session -t "$sess" "$pri $rest"
